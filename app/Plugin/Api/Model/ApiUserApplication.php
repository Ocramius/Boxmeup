<?php

class ApiUserApplication extends ApiAppModel {

	public $name = 'ApiUserApplication';

	public $displayField = 'token';

	public $validate = array(
		'user_id' => array(
			'numeric' => array(
				'rule' => array('numeric')
			),
			'notempty' => array(
				'rule' => array('notempty')
			)
		),
		'token' => array(
			'notempty' => array(
				'rule' => array('notempty')
			)
		)
	);

	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	/**
	 * Creates an access token entry for a user with a given application name.
	 *
	 * @param string $name
	 * @param integer $userId
	 * @return string
	 * @throws InternalErrorException
	 */
	public function createApplication($name, $userId) {
		$token = sha1($userId . time());
		$result = $this->save(array(
			'ApiUserApplication' => array(
				'name' => $name,
				'user_id' => $userId,
				'token' => $token
			)
		));
		if (!$result) {
			throw new InternalErrorException('Unable to create oauth token');
		}
		return $token;
	}

	/**
	 * Retrieve the user's auth token by userId and application name.
	 *
	 * @param integer $userId
	 * @param string $name Application name
	 * @return string
	 * @throws NotFoundException
	 */
	public function getTokenByUserId($userId, $name) {
		$key = $userId . $name . '_auth_token';
		if (!$token = Cache::read($key)) {
			$result = $this->find('first', array(
				'conditions' => array(
					'ApiUserApplication.user_id' => $userId,
					'ApiUserApplication.name' => $name
				),
				'fields' => array('ApiUserApplication.token')
			));
			if (!empty($result['ApiUserApplication']['token'])) {
				$token = $result['ApiUserApplication']['token'];
				Cache::write($key, $token);
			}
		}
		if (empty($token)) {
			throw new NotFoundException('Token does not exist for this user.');
		}
		return $token;
	}

}
