<?php

namespace App\Models;

use CodeIgniter\Model;

class Product_model extends Model {

	protected $table = 'tbl_product';

	protected $primaryKey = 'prod_id';

	protected $allowedFields = [
        'prod_id', 'prod_name', 'prod_price', 'prod_pc_id', 'prod_note'
    ];

	protected $validationRules= [
		'prod_name'  =>  'required|min_length[2]|max_length[100]',
		'prod_price' =>  'required|decimal',
		'prod_pc_id' =>  'required|is_not_unique[tbl_product_category.pc_id]'
	];

	protected $validationMessages= [
		'prod_name' => [
			'required' => 'Product name is required.',
			'min_length' => 'Minimum 2 characters.',
			'max_length' => 'Maximum 100 characters.',
		],
		'prod_price' => [
			'required' => 'Price is required.',
			'decimal' => 'Price Must be Number.',
		],
		'prod_pc_id' => [
			'required' => 'Category is required.',
			'is_not_unique' => 'Category Must be in Product Category.',
		],
	];

	//-------------------------------------------------
	public function findAll(int $limit=0, int $offset=0) {
		$builder = $this->builder();
		$builder->select('*');
		$builder->join('tbl_product_category', 'pc_id = prod_pc_id', 'left');
		$query = $builder->get($limit, $offset);
		return $query->getResult();
	}
	//-------------------------------------------------
	public function find($id = null) {
		$builder = $this->builder();
		$builder->select('tbl_product.*, tbl_product_category.pc_name');
		$builder->join('tbl_product_category', 'pc_id = prod_pc_id', 'left');
		$builder->where('prod_id', $id);
		$query = $builder->get();
		return $query->getRow();
	}
	//-------------------------------------------------
	public function getNewId() {
		$builder = $this->builder();
		$builder->selectMax('prod_id');

		$query = $builder->get();

		if ($query->getNumRows() > 0) {
			$row = $query->getRow();
			$max_id = substr($row->prod_id, 1);
			$new_id = $max_id + 1;
			if ($new_id < 10) {
				$new_id = "P00000" . $new_id;
			} elseif ($new_id < 100) {
				$new_id = "P0000" . $new_id;
			} elseif ($new_id < 1000) {
				$new_id = "P000" . $new_id;
			} elseif ($new_id < 10000) {
				$new_id = "P00" . $new_id;
			} elseif ($new_id < 100000) {
				$new_id = "P0" . $new_id;
			} else {
				$new_id = "P" . $new_id;
			}
			return $new_id;
		} else {
			return "P000001";
		}
	}
	//-------------------------------------------------
}
