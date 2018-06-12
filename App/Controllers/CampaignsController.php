<?php

namespace App\Controllers;

use Jacwright\RestServer\RestException;
use App\Models\CampaignsModel;
use App\Objects\Campaign;

class CampaignsController {

	/**
	 * @url POST /
	 * @return object
	 */
	public function create() {
		if (!isset($_POST['name']) || empty($_POST['name'])) {
			throw new RestException(401, 'Field `name` must be defined to create a campaign');
		}

		return CampaignsModel::save(new Campaign((object) [
							'name' => $_POST['name'],
		]));
	}

	/**
	 * @url GET /
	 * @return array
	 */
	public function readAll() {
		$campaigns = CampaignsModel::get();

		foreach ($campaigns as &$campaign) {
			unset($campaign->id);
		}

		return $campaigns;
	}

	/**
	 * @url GET /$id
	 * @return object
	 */
	public function read($id = null) {
		$campaign = CampaignsModel::get($id);

		unset($campaign->id);

		return $campaign;
	}

	/**
	 * @url PUT /$id
	 * @return object
	 */
	public function update($id = null) {
		$campaign = CampaignsModel::update(new Campaign((object) [
							'id' => $id,
							'name' => $_PUT['name'],
		]));

		return $campaign;
	}

	/**
	 * @url DELETE /
	 * @return object
	 */
	public function deleteAll() {
		return CampaignsModel::delete();
	}

	/**
	 * @url DELETE /$id
	 * @return object
	 */
	public function delete($id = null) {
		return CampaignsModel::delete($id);
	}

}
