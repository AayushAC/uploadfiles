<?php
namespace Opencart\Catalog\Controller\Api;

class Productlist extends \Opencart\System\Engine\Controller {
    public function index(): void {
        // Skip API key validation, allow access for all users
        // No need for $api_key or $valid_api_key

        // Load models
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        // Get all products
        $products = $this->model_catalog_product->getProducts();
        $product_data = [];

        foreach ($products as $product) {
            // Get image URL
            $image = $this->model_tool_image->resize($product['image'], 500, 500);

            // Format product data
            $product_data[] = [
                'id'           => $product['product_id'],
                'name'         => $product['name'],
                'price'        => $product['price'],
                'special'      => $product['special'] ? $product['special'] : null,
                'quantity'     => $product['quantity'],
                'sku'          => $product['sku'],
                'model'        => $product['model'],
                'image'        => $image,
                'category'     => $this->getCategoryName($product['product_id']),
                'url'          => $this->url->link('product/product', 'product_id=' . $product['product_id']),
                'availability' => $product['quantity'] > 0 ? 'In Stock' : 'Out of Stock'
            ];
        }

        // Set JSON response
        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($product_data, JSON_PRETTY_PRINT));
    }

    // Helper function to get category name
    private function getCategoryName($product_id) {
        $query = $this->db->query("SELECT cd.name FROM " . DB_PREFIX . "category_description cd 
            JOIN " . DB_PREFIX . "product_to_category pc ON cd.category_id = pc.category_id 
            WHERE pc.product_id = '" . (int)$product_id . "' LIMIT 1");

        return $query->num_rows ? $query->row['name'] : 'Uncategorized';
    }
}
