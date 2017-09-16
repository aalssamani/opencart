<?php
class ModelLocalisationBusiness extends Model {
	public function addBusiness($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "business SET user_id = '" . $this->db->escape($data['user_id']) . "',name = '" . $this->db->escape($data['name']) . "', address = '" . $this->db->escape($data['address']) . "', geocode = '" . $this->db->escape($data['geocode']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', image = '" . $this->db->escape($data['image']) . "', open = '" . $this->db->escape($data['open']) . "', comment = '" . $this->db->escape($data['comment']) . "'");
	
		return $this->db->getLastId();
	}

	public function editBusiness($business_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "business SET user_id = '" . $this->db->escape($data['user_id']) . "',name = '" . $this->db->escape($data['name']) . "', address = '" . $this->db->escape($data['address']) . "', geocode = '" . $this->db->escape($data['geocode']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', image = '" . $this->db->escape($data['image']) . "', open = '" . $this->db->escape($data['open']) . "', comment = '" . $this->db->escape($data['comment']) . "' WHERE business_id = '" . (int)$business_id . "'");
	}

	public function deleteBusiness($business_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "business WHERE business_id = " . (int)$business_id);
	}

	public function getBusiness($business_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "business WHERE business_id = '" . (int)$business_id . "'");

		return $query->row;
	}

	public function getBusinesses($data = array()) {
		$sql = "SELECT business_id, name, address FROM " . DB_PREFIX . "business";

		$sort_data = array(
			'name',
			'address',
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalBusinesses() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "business");

		return $query->row['total'];
	}
}
