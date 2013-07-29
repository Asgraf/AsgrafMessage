<?php
App::uses('Component', 'Controller');
/**
 * Sends message to user and redirects using proper format
 * @author Michal Turek <asgraf@gmail.com>
 * @link https://github.com/Asgraf/AsgrafMessage
 */
class MessageComponent extends Component {
	/**
	 * @var Controller
	 */
	private $controller = null;
	
	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}
	
	public function flash($msg,$url=null,$type='neutral',$metadata=array()) {
		if(!is_array($metadata)) throw new InternalErrorException(__('Invalid metadata value. Array expected'));
		if(empty($this->controller->request->params['ext'])) {
			if(!empty($this->controller->Session)) {
				$this->controller->Session->setFlash($msg,'default',array(),$type);
				if(!empty($url)) $this->controller->redirect($url);
			} else {
				$this->controller->flash($msg,$url);
			}
		} else {
			$this->controller->set('response',array_merge(array('message'=>$msg,'redirect'=>Router::url($url),'type'=>$type),$metadata));
			$this->controller->set('_serialize','response');
		}
	}
}