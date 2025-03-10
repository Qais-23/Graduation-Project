<?php

include 'database.php';

// Function to calculate similarity between two products
function calculateSimilarity($productA, $productB)
{
    // Extract keywords from descriptions
    $keywordsA = extractKeywords($productA['Product_Description']);
    $keywordsB = extractKeywords($productB['Product_Description']);

    // Extract first and second words from names
    $nameWordsA = preg_split('/\s+/', strtolower($productA['Product_name']));
    $nameWordsB = preg_split('/\s+/', strtolower($productB['Product_name']));

    // Compare the first word of the product name
    $firstWordSimilarity = ($nameWordsA[0] === $nameWordsB[0]) ? 1 : 0;

    // If the second word matches or both second words are numeric, exclude the product from recommendations
    if (
        isset($nameWordsA[1], $nameWordsB[1]) &&
        ($nameWordsA[1] === $nameWordsB[1] || (is_numeric($nameWordsA[1]) && is_numeric($nameWordsB[1])))
    ) {
        return -1; // Negative similarity score to exclude the product
    }

    // Common keywords in description
    $commonKeywords = array_intersect($keywordsA, $keywordsB);

    // Category similarity
    $categorySimilarity = ($productA['Product_category'] === $productB['Product_category']) ? 1 : 0;

    // Size similarity
    $sizeSimilarity = ($productA['Product_Size'] === $productB['Product_Size']) ? 1 : 0;

    // Weights for similarity components
    $weights = [
        'keywords' => 2, // Weight for common keywords
        'first_word' => 2, // Weight for first word match
        'category' => 1, // Weight for category match
        'size' => 1 // Weight for size match
    ];

    // Weighted score calculation
    return ($weights['keywords'] * count($commonKeywords)) +
        ($weights['first_word'] * $firstWordSimilarity) +
        ($weights['category'] * $categorySimilarity) +
        ($weights['size'] * $sizeSimilarity);
}

// Function to extract keywords from text
function extractKeywords($text)
{
    $stopWords = ['the', 'and', 'is', 'in', 'of', 'on', 'for', 'with', 'a', 'an'];
    // Split text into words and convert to lowercase
    $words = preg_split('/\W+/', strtolower($text));
    // Remove stop words and empty strings
    $filteredWords = array_filter($words, function ($word) use ($stopWords) {
        return !in_array($word, $stopWords) && !empty($word);
    });
    return $filteredWords;
}

// Function to generate recommendations with a similarity threshold
function generateRecommendations($customerID, $pdo, $k = 1, $similarityThreshold = 7)
{
    // Get customer's purchased products
    $stmt = $pdo->prepare("SELECT DISTINCT product_id FROM order_items WHERE CustomerID = ?");
    $stmt->execute([$customerID]);
    $purchasedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $purchasedProductIDs = array_column($purchasedProducts, 'product_id');

    // Get already recommended products for this customer
    $recommendedStmt = $pdo->prepare("SELECT DISTINCT product_id FROM recommended_products WHERE customerID = ?");
    $recommendedStmt->execute([$customerID]);
    $alreadyRecommendedProducts = array_column($recommendedStmt->fetchAll(PDO::FETCH_ASSOC), 'product_id');

    // Get deleted recommendations
    $deletedStmt = $pdo->prepare("SELECT product_id FROM deleted_recommendations WHERE customerID = ?");
    $deletedStmt->execute([$customerID]);
    $deletedProducts = array_column($deletedStmt->fetchAll(PDO::FETCH_ASSOC), 'product_id');

    $recommendations = [];
    $recommendedProductIDs = $alreadyRecommendedProducts; // Start with already recommended products

    // Step 1: Check if the customer has no purchases
    if (empty($purchasedProductIDs)) {
        // Fetch "most-viewed" products
        $mostViewedStmt = $pdo->query("
            SELECT product_id, SUM(view_count) AS total_views 
            FROM product_views 
            GROUP BY product_id 
            HAVING total_views >= 4 
            ORDER BY total_views DESC 
            LIMIT 4
        ");
        $mostViewedProducts = $mostViewedStmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($mostViewedProducts as $mostViewedProduct) {
            if (
                !in_array($mostViewedProduct['product_id'], $deletedProducts) && // Exclude deleted recommendations
                !in_array($mostViewedProduct['product_id'], $recommendedProductIDs) // Exclude already recommended products
            ) {
                $recommendations[] = [
                    'customerID' => $customerID,
                    'product_id' => $mostViewedProduct['product_id'],
                    'similarity_score' => 'Viewed' // Mark as "most-viewed"
                ];
                $recommendedProductIDs[] = $mostViewedProduct['product_id']; // Add to tracking array
            }
        }

        // Save "most-viewed" recommendations to the database
        if (!empty($recommendations)) {
            foreach ($recommendations as $recommendation) {
                $stmt = $pdo->prepare("INSERT INTO recommended_products (customerID, product_id, similarity_score) VALUES (?, ?, ?)");
                $stmt->execute([
                    $recommendation['customerID'],
                    $recommendation['product_id'],
                    $recommendation['similarity_score']
                ]);
            }
        }

        // Return only "most-viewed" recommendations
        return ['recommendations' => $recommendations, 'comparisonData' => []];
    }

    // Step 2: Handle customers with purchases (existing logic)

    // Identify new purchased products (not already recommended)
    $newPurchases = array_diff($purchasedProductIDs, $alreadyRecommendedProducts);

    // If no new purchases, skip further processing
    if (empty($newPurchases)) {
        return ['recommendations' => [], 'comparisonData' => []];
    }

    // Fetch all products
    $productsStmt = $pdo->query("SELECT * FROM products");
    $allProducts = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Similarity-based recommendations
    foreach ($newPurchases as $productID) {
        $productDetailsStmt = $pdo->prepare("SELECT * FROM products WHERE Product_id = ?");
        $productDetailsStmt->execute([$productID]);
        $productA = $productDetailsStmt->fetch(PDO::FETCH_ASSOC);

        $similarities = [];

        foreach ($allProducts as $productB) {
            if (
                $productA['Product_id'] !== $productB['Product_id'] && // Exclude itself
                !in_array($productB['Product_id'], $deletedProducts) && // Exclude deleted recommendations
                !in_array($productB['Product_id'], $purchasedProductIDs) && // Exclude purchased products
                !in_array($productB['Product_id'], $recommendedProductIDs) // Exclude already recommended products
            ) {
                $similarityScore = calculateSimilarity($productA, $productB);
                if ($similarityScore >= $similarityThreshold && $similarityScore != -1) {
                    $similarities[] = [
                        'product' => $productB,
                        'score' => $similarityScore
                    ];
                }
            }
        }

        usort($similarities, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $topK = array_slice($similarities, 0, $k);
        foreach ($topK as $similarity) {
            $recommendations[] = [
                'customerID' => $customerID,
                'product_id' => $similarity['product']['Product_id'],
                'similarity_score' => $similarity['score']
            ];
            $recommendedProductIDs[] = $similarity['product']['Product_id']; // Add to tracking array
        }
    }

    // Save recommendations to the database
    if (!empty($recommendations)) {
        foreach ($recommendations as $recommendation) {
            $stmt = $pdo->prepare("INSERT INTO recommended_products (customerID, product_id, similarity_score) VALUES (?, ?, ?)");
            $stmt->execute([
                $recommendation['customerID'],
                $recommendation['product_id'],
                $recommendation['similarity_score']
            ]);
        }
    }

    return ['recommendations' => $recommendations, 'comparisonData' => []];
}