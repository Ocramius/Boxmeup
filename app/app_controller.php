<?php
class AppController extends Controller {
	public $components = array(
		'Auth',
		'RequestHandler',
		'Session',
		'Cookie',
		'Security',
		'DebugKit.Toolbar' => array('panels' => array('history' => false))
	);
	public $helpers = array('Html', 'Form', 'Session', 'Paginator');

	public $_secure = false;

	public function beforeFilter() {
		$this->setupAuth();
	}

	public function beforeRender() {
		$User = $this->Auth->user();
		$this->set(array(
			'User' => $User[$this->Auth->userModel],
			'Webroot' => $this->webroot,
			'Fullwebroot' => env('SERVER_NAME') . $this->webroot,
			'Here' => $this->here
		));
		
		if(Configure::read('Site.theme')) {
			$this->view = 'Theme';
			$this->theme = Configure::read('Site.theme');
		}

		if($this->isMobile()) {
			Configure::write('debug', 1);
			$this->view = 'Theme';
			$this->theme = Configure::read('Site.mobile_theme') ?
				Configure::read('Site.mobile_theme') :
				'mobile';
			$this->layout = 'mobile';
			$this->autoLayout = true;
			$this->autoRender = true;
			$this->set('mobile_page_id', $this->name.$this->action);
		}
	}

	/**
	 * Setup the authentication component.
	 */
	private function setupAuth() {
		Security::setHash(Configure::read('Security.hash'));

		$this->Auth->userModel = 'User';
		$this->Auth->loginAction = '/login';
		$this->Auth->loginRedirect = !$this->isAdmin() ? '/dashboard' : '/';
		$this->Auth->logoutRedirect = '/';
		$this->Auth->loginError = 'No username and password was found with that combination';
		$this->Auth->userScope = array('User.is_active' => 1);

		$this->Auth->flashElement = 'notification/error';

		$this->Auth->fields = array(
			'username' => 'email',
			'password' => 'password'
		);

		if($this->isMobile())
			$this->Auth->loginRedirect = '/containers';

		// Setup a scope for admin users accessing admin section
		if($this->isAdmin()) {
			// If a user is logged in, but is not an admin...
			if($this->Auth->user() && !$this->Auth->user('is_admin')) {
				$this->Session->setFlash(__('Not authorized to view this resource.', true), 'notification/error');
				$this->redirect('/');
			}
				
			$this->Auth->userScope = array_merge($this->Auth->userScope, array('User.is_admin' => 1));
			$this->Auth->loginAction = '/admin/login';
			$this->layout = 'admin';
		}

		if($this->_secure || $this->isAdmin())
			$this->Auth->deny();
		else
			$this->Auth->allow();

	}

	protected function isAdmin() {
		return isset($this->params['admin']) && $this->params['admin'];
	}

	/**
	 * Verifies the current user can perform sensitive actions on container.
	 *
	 * @param <mixed> $id Can be either integer ID or UUID
	 */
	protected function verifyUser($id) {
		if(strstr($id, '-') !== false)
			$id = ClassRegistry::init('Container')->getIdByUUID($id);
		if(!ClassRegistry::init('Container')->verifyContainerUser($id, $this->Auth->user('id'))) {
			$this->Session->setFlash(__('Not authorized to perform actions on this container', true), 'notification/error');
			$this->redirect(array(
				'controller' => 'containers',
				'action' => 'index'
			));
		}
	}

	/**
	 * Determines if the client is a mobile device.
	 * 
	 * @return <boolean>
	 */
	protected function isMobile() {
		return $this->RequestHandler->isMobile() && !$this->Session->read('mobile_disabled');
	}
}
