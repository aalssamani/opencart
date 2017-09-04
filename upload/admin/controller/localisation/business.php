<?php
class ControllerLocalisationBusiness extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/business');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/business');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/business');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/business');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_business->addBusiness($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/business', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/business');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/business');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_business->editBusiness($this->request->get['business_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/business', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/business');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/business');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $business_id) {
				$this->model_localisation_business->deleteBusiness($business_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/business', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] =   array();

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('heading_title'),
			'href' =>  $this->url->link('localisation/business', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('localisation/business/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('localisation/business/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['business'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$business_total = $this->model_localisation_business->getTotalBusinesses();

		$results = $this->model_localisation_business->getBusinesses($filter_data);

		foreach ($results as $result) {
			$data['business'][] =   array(
				'business_id' => $result['business_id'],
				'name'        => $result['name'],
				'address'     => $result['address'],
				'edit'        => $this->url->link('localisation/business/edit', 'user_token=' . $this->session->data['user_token'] . '&business_id=' . $result['business_id'] . $url, true)
			);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('localisation/business', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url, true);
		$data['sort_address'] = $this->url->link('localisation/business', 'user_token=' . $this->session->data['user_token'] . '&sort=address' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $business_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/business', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($business_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($business_total - $this->config->get('config_limit_admin'))) ? $business_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $business_total, ceil($business_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/business_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['business_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['address'])) {
			$data['error_address'] = $this->error['address'];
		} else {
			$data['error_address'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/business', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['business_id'])) {
			$data['action'] = $this->url->link('localisation/business/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('localisation/business/edit', 'user_token=' . $this->session->data['user_token'] .  '&business_id=' . $this->request->get['business_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('localisation/business', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['business_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$business_info = $this->model_localisation_business->getBusiness($this->request->get['business_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];
                
                $this->load->model('user/user');

		$data['users'] = $this->model_user_user->getUsers(null);

		$this->load->model('setting/store');

                if (isset($this->request->post['user_id'])) {
			$data['user_id'] = $this->request->post['user_id'];
		} elseif (!empty($business_info)) {
			$data['user_id'] = $business_info['user_id'];
		} else {
			$data['user_id'] =   '';
		}
                
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($business_info)) {
			$data['name'] = $business_info['name'];
		} else {
			$data['name'] =   '';
		}

		if (isset($this->request->post['address'])) {
			$data['address'] = $this->request->post['address'];
		} elseif (!empty($business_info)) {
			$data['address'] = $business_info['address'];
		} else {
			$data['address'] = '';
		}

		if (isset($this->request->post['geocode'])) {
			$data['geocode'] = $this->request->post['geocode'];
		} elseif (!empty($business_info)) {
			$data['geocode'] = $business_info['geocode'];
		} else {
			$data['geocode'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($business_info)) {
			$data['telephone'] = $business_info['telephone'];
		} else {
			$data['telephone'] = '';
		}
		
		if (isset($this->request->post['fax'])) {
			$data['fax'] = $this->request->post['fax'];
		} elseif (!empty($business_info)) {
			$data['fax'] = $business_info['fax'];
		} else {
			$data['fax'] = '';
		}
		
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($business_info)) {
			$data['image'] = $business_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($business_info) && is_file(DIR_IMAGE . $business_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($business_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['open'])) {
			$data['open'] = $this->request->post['open'];
		} elseif (!empty($business_info)) {
			$data['open'] = $business_info['open'];
		} else {
			$data['open'] = '';
		}

		if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		} elseif (!empty($business_info)) {
			$data['comment'] = $business_info['comment'];
		} else {
			$data['comment'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/business_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/business')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['address']) < 3) || (utf8_strlen($this->request->post['address']) > 128)) {
			$this->error['address'] = $this->language->get('error_address');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/business')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}