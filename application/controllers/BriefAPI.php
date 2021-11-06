<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BriefAPI extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('welcome_message');
    }

    // API main
    public function getProduct($productID = "")
    {
        header('Content-Type: application/json');
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $this->load->model("briefModel");
                if ($productID) {
                    $productID = htmlspecialchars($productID);
                    if (is_numeric($productID)) {
                        $data = $this->getProductSingle($productID);
                    } else {
                        $data = array("status" => 400, "msg" => "Bad Request", "data" => null
                        );
                    }
                } else {
                    $data = $this->getProductAll();
                }
            } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = $this->addProduct(
                    $this->input->post("product_id"),
                    $this->input->post("product_name"),
                    $this->input->post("product_adress"),
                    $this->input->post("product_created_date"));
            } else {
                $data = array("status" => 405, "msg" => "Method Not Allowed", "data" => null
                );
            }
            echo json_encode($data);
        } catch (Exception $ex) {
            $data = array("status" => 500, "msg" => "Internal Server Error", "data" => null
            );
        }

    }

    //return All Product
    private function getProductAll()
    {
        try {
            $getProduct = $this->briefModel->getTable("product");
            if ($getProduct) {
                return array("status" => 200, "msg" => "Success", "data" => $getProduct
                );
            } else {
                return array("status" => 204, "msg" => "No Content", "data" => $getProduct
                );
            }
        } catch (Exception $ex) {
            return array("status" => 500, "msg" => "Internal Server Error", "data" => null
            );
        }

    }

    //return Single Product
    private function getProductSingle($productId = "")
    {
        try {
            $getProduct = $this->briefModel->getTable("product", array("product_id" => $productId));
            if ($getProduct) {
                return array("status" => 200, "msg" => "Success", "data" => $getProduct
                );
            } else {
                return array("status" => 204, "msg" => "No Content", "data" => $getProduct
                );
            }
        } catch (Exception $ex) {
            return array("status" => 500, "msg" => "Internal Server Error", "data" => null
            );
        }

    }

    //add product
    private function addProduct($product_id = "", $product_name = "", $adress = "", $created_date = "")
    {
        try {
            $this->load->helper("functions");
            $this->load->model("briefModel");
            if ($product_id && $product_name && $adress && $created_date && is_numeric($product_id) && checkStringDate($created_date)) {
                $controlProduct = $this->briefModel->getTableSingle("product", array("product_id" => $product_id));
                if (!$controlProduct) {
                    $name = htmlspecialchars($product_name);
                    $save = $this->briefModel->add_new(array(
                        "product_id" => $product_id,
                        "name" => $product_name,
                        "adress" => $adress,
                        "created_date" => $created_date
                    ), "product");
                    if ($save) {
                        $getProduct = $this->briefModel->getTableSingle("product", array("product_id" => $product_id));
                        return array("status" => 200, "msg" => "Success", "data" => $getProduct
                        );
                    } else {
                        return array("status" => 204, "msg" => "Save Problem", "data" => null
                        );
                    }
                } else {
                    return array("status" => 204, "msg" => "Product Avaible", "data" => null
                    );
                }
            }else{
                return array("status" => 400, "msg" => "Bad Request", "data" => null
                );
            }
        } catch (Exception $ex) {
            return array("status" => 500, "msg" => "Internal Server Error", "data" => null
            );
        }
    }


}
