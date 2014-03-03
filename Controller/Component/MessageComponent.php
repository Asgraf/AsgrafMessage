<?php
App::uses('Component', 'Controller');
/**
 * Sends message to user and redirects using proper format
 * @author Michal Turek <asgraf@gmail.com>
 * @link https://github.com/Asgraf/AsgrafMessage
 */
class MessageComponent extends Component {
	private $options = array();
	/**
	 * @var Controller
	 */
	private $controller = null;
	
	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

	/**
	 * @param string $msg Message to flash
	 * @param mixed  $url Url to redirect or null for no redirect
	 * @param string $type flash message id
	 * @param array  $metadata array additional options to return when json or xml is requested
	 *
	 * @return array|bool|null
	 * @throws InternalErrorException
	 */
	public function flash($msg,$url=null,$type='neutral',$metadata=array()) {
		if(!is_array($metadata)) throw new InternalErrorException(__('Invalid metadata value. Array expected'));
		if(!$this->controller->request['ext'] && !$this->controller->request['requested']) {
			if(!empty($this->controller->Session)) {
				$metadata['type']=$type;
				$this->controller->Session->setFlash($msg,Hash::get($this->settings,'element')?:'default',$metadata,$type);
				if(!empty($url)) return $this->controller->redirect($url) || null;
			} else {
				$this->controller->flash($msg,$url);
			}
			return null;
		} else {
			$response = array_merge(array('message'=>$msg,'redirect'=>Router::url($url),'type'=>$type),$metadata);
			$this->controller->set('response',$response);
			$this->controller->set('_serialize','response');
			return $response;
		}
	}
}