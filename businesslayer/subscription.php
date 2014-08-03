<?php
/**
 * ownCloud - Calendar App
 *
 * @author Georg Ehrke
 * @copyright 2014 Georg Ehrke <oc.list@georgehrke.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\Calendar\BusinessLayer;

use OCP\AppFramework\Http;
use OCP\Calendar\ISubscription;
use OCP\Calendar\ISubscriptionCollection;
use OCP\Calendar\DoesNotExistException;
use OCP\Calendar\MultipleObjectsReturnedException;

class SubscriptionBusinessLayer extends BusinessLayer {

	/**
	 * @var \OCA\Calendar\Db\SubscriptionMapper
	 */
	protected $mapper;


	/**
	 * get all subscriptions of a user
	 * @param string $userId
	 * @param integer $limit
	 * @param integer $offset
	 * @return ISubscriptionCollection
	 */
	public function findAll($userId, $limit, $offset) {
		return $this->mapper->findAll($userId, $limit, $offset);
	}


	/**
	 * get all subscriptions of a certain type
	 * @param string $userId
	 * @param string $type
	 * @param integer $limit
	 * @param integer $offset
	 * @return ISubscriptionCollection
	 */
	public function findAllByType($userId, $type, $limit, $offset) {
		return $this->mapper->findAllByType($userId, $type, $limit, $offset);
	}


	/**
	 * count number of user's subscriptions
	 * @param string $userId
	 * @return integer
	 */
	public function count($userId=null) {
		return $this->mapper->count($userId);
	}


	/**
	 * count number of user's subscriptions of a certain type
	 * @param string $type
	 * @param string $userId
	 * @return integer
	 */
	public function countByType($type, $userId=null) {
		return $this->mapper->countByType($type, $userId);
	}


	/**
	 * get a subscription
	 * @param int $id
	 * @param string $userId
	 * @throws BusinessLayerException
	 * @return ISubscription
	 */
	public function find($id, $userId=null) {
		try {
			return $this->mapper->find($id, $userId);
		} catch(DoesNotExistException $ex) {
			throw new BusinessLayerException($ex->getMessage(), Http::STATUS_NOT_FOUND, $ex);
		} catch(MultipleObjectsReturnedException $ex) {
			throw new BusinessLayerException($ex->getMessage(), HTTP::STATUS_INTERNAL_SERVER_ERROR, $ex);
		}
	}


	/**
	 * get a subscription by type
	 * @param int $id
	 * @param string $type
	 * @param string $userId
	 * @throws BusinessLayerException
	 * @return ISubscription
	 */
	public function findByType($id, $type, $userId=null) {
		try {
			return $this->mapper->findByType($id, $type, $userId);
		} catch(DoesNotExistException $ex) {
			throw new BusinessLayerException($ex->getMessage(), Http::STATUS_NOT_FOUND, $ex);
		} catch(MultipleObjectsReturnedException $ex) {
			throw new BusinessLayerException($ex->getMessage(), HTTP::STATUS_INTERNAL_SERVER_ERROR, $ex);
		}
	}


	/**
	 * get whether or not a subscription exists
	 * @param int $id
	 * @param string $userId
	 * @return bool
	 */
	public function doesExist($id, $userId=null) {
		return $this->mapper->doesExist($id, $userId);
	}


	/**
	 * get whether or not a subscription exists of a certain type
	 * @param int $id
	 * @param string $type
	 * @param string $userId
	 * @return boolean
	 */
	public function doesExistOfType($id, $type, $userId=null) {
		return $this->mapper->doesExistOfType($id, $type, $userId);
	}


	/**
	 * create a new subscription
	 * @param ISubscription $subscription
	 * @throws BusinessLayerException
	 * @return ISubscription
	 */
	public function create(ISubscription $subscription) {
		$this->checkIsValid($subscription);
		return $this->mapper->insert($subscription);
	}


	/**
	 * update a subscription
	 * @param ISubscription $subscription
	 * @throws BusinessLayerException
	 * @return ISubscription
	 */
	public function update(ISubscription $subscription) {
		$this->checkIsValid($subscription);
		$this->mapper->update($subscription);
		return $subscription;
	}


	/**
	 * delete a subscription
	 * @param ISubscription $subscription
	 */
	public function delete(ISubscription $subscription) {
		$this->mapper->delete($subscription);
	}
}