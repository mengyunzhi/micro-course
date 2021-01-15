<?php
namespace app\index\controller;
use think\Controller;
use app\common\model\Classroom;
use think\Request;
use think\validate;
use app\common\model\SeatMap;
use app\common\model\Course;
use app\common\model\Grade;
use app\common\model\SeatAisle;
use app\common\model\Teacher;
/**
 * 座位图模板
 */
class SeatMapController extends Controller {
	public function index(){
		$name = input('param.name');
		$seatMaps = new SeatMap;
		if(!is_null($name)) {
			$seatMaps = SeatMap::where('name', 'like', '%' . $name . '%');
		}
		$seatMaps = $seatMaps->order('id desc')->paginate(5);
		$this->assign('seatMaps', $seatMaps);
		return $this->fetch();
	}
	
	/**
	 * 座位图模板显示
	 */
	public function template() {

		// 判断有无模板
		if(empty(SeatMap::all())) {
			return $this->error('当前不存在座位图模板', $_SERVER["HTTP_REFERER"]);
		}

		$match = input('param.match');
		
		$url = '';
		//判断上一级路由是否是教室那边
		//字符串的模式匹配
		if (preg_match("/add/i", $_SERVER["HTTP_REFERER"]) || !empty($match)) {
    		$url = 'classroom/add';
    		$match = 1;
		} else if(preg_match("/edit/i", $_SERVER["HTTP_REFERER"]) || !empty($match)) {
			$url = 'classroom/edit';
			$match = 1;
		}
		$classroomId = Request::instance()->param('classroomId');
		$classroomName = Request::instance()->param('classroomName');
		$seatMapAsc = SeatMap::order('id')->select();
		$seatMapDesc = SeatMap::order('id desc')->select();
		$id = Request::instance()->param('id/d');
		
		// 若是最后一个则下一个模板为最开始的模板
		if($id == -2 || is_null($id)) {
			$seatmap = $seatMapAsc[0];
			$id = $seatmap->id;
		}
		// 若是第一个模板则上一个模板是最后的模板
		if($id === -1) {
			$seatmap = $seatMapDesc[0];
			$id = $seatmap->id;
		}
		$course_id = Request::instance()->param('course_id');
		// 实例化课程
		$Course = Course::get($course_id);
		$SeatMap = SeatMap::get($id);
		$seatAisle = new SeatAisle;
		$seatAisle = SeatAisle::order('id asc')->where('seat_map_id', '=', $id)->select();
		if(empty($seatAisle)) {
			return $this->error('当前模板座位图为空，请重新编辑或删除该模板', url('index'));
		}

		$this->assign('match', $match);
		$this->assign('classroomName', $classroomName);
		$this->assign('classroomId', $classroomId);
		$this->assign('url', $url);
		$this->assign('seatMap1', $seatMapAsc);
		$this->assign('seatMap2', $seatMapDesc);
		$this->assign('SeatMap',$SeatMap);
		$this->assign('seatAisles', $seatAisle);
		$this->assign('Course',$Course);
		return $this->fetch();

	}
	/**
	 * 返回座位图模板数据库里面id升序的数组
	 */
	public function asc() {
		return SeatMap::order('id')->select();
		
	}
	/**
	 * 返回座位图模板数据库id降序的数组
	 * @param $arry 要逆置的数组
	 */
	public function desc($arry) {
		return array_reverse ($arry);
	}

	/**
	 * 增加和编辑模板
	 */
	public function add() {
		$id = input('id');
		$SeatMap = new SeatMap;
		$SeatMap->name = '';
		$SeatMap->x_map = '';
		$SeatMap->y_map = '';
		$SeatMap1 = SeatMap::get(['id' => $id]);

		//若传来的$id不为空，即执行编辑操作
		if(!is_null($SeatMap1)) {
			$SeatMap = $SeatMap1;
		}
		$this->assign('SeatMap', $SeatMap);
		return $this->fetch();
	}
	/**
	 * 编辑模板座位图的过道以及座位
	 * 设置每个座位是过道还是座位
	 */
	public function edit() {
		$id = Request::instance()->param('id/d');

		//判断当前有无使用此模板的教室上课
		if($this->judgeClassroom($id)) {
			$seatAisle = SeatAisle::where('seat_map_id', '=', $id)->select();
			rsort($seatAisle);
			$seatAisle = array_reverse($seatAisle);
			$this->assign('seatAisles', $seatAisle);
			$SeatMap = new SeatMap;
			$SeatMap = SeatMap::get($id);
			$this->assign('SeatMap', $SeatMap);
			return $this->fetch();
		}
	}

	/**
	 * 修改对应被修改模板的教室
	 */
	public function editClassroom() {
		$seatMapId = input('seatMapId');
		$classrooms = Classroom::where('seat_map_id', '=', $seatMapId)->select();
		if(!empty($classrooms)) {
			foreach ($classrooms as $Classroom) {
				$ClassroomController = new ClassroomController;
				$ClassroomController->deleteSeat($Classroom->id);
				$ClassroomController->saveSeat($seatMapId, $Classroom->id);
			}
		}
		return $this->success('模板编辑成功', url('index'));
	}

	/**
	 * 保存模板的行和列
	 * 通过模板名字来判断是不是已经添加了模板（解决添加模板时行列输入错误的问题，同时也起到了编辑的作用）
	 */
	public function save() {
		$SeatMap = new SeatMap;
		$SeatMap->name = input('post.name');
		$SeatMapPre = SeatMap::where('name', '=', input('post.name'))->select();
		
		//如果是模板编辑
		if(!empty($SeatMapPre)) {
			$idPre = $SeatMapPre[0]->id;
			$this->deleteSeatAisle($idPre);
			$SeatMapPre[0]->delete();
		}
		//首个座位图是第一个
		$seatMaps = SeatMap::all();
		if(empty($seatMaps)) {
			$SeatMap->is_first = 1;
		}

		$SeatMap->x_map = Request::instance()->post('xMap');
		$SeatMap->y_map = Request::instance()->post('yMap');
		if(!$SeatMap->save()) {
			return $this->error('保存信息错误'.$SeatMap->getError());
		}
		$id = $SeatMap->id;
		$SeatMap = SeatMap::all();

		// 将新增的模板设置为最后一个
		foreach ($SeatMap as $seatMap) {
			if($seatMap->id != $id) {
				$seatMap->is_last = 0;
				$seatMap->save();
			}
		}
		$this->addseatAisle($id, url('edit?id=' . $id));
	}

	/**
	 * 利用双重for循环挨个存储座位模板的座位
	 */
	public function addseatAisle($seatMapId, $url) {
		$seatmap = SeatMap::get($seatMapId);

		// 建立模板座位图
		for($i = 0; $i < $seatmap->x_map; $i++) {
			for($j = 0; $j < $seatmap->y_map; $j++) {
				$seatAisle = new seatAisle;
				if(!$this->saveSeatAisle($seatMapId, $seatAisle, $i, $j)) {
					return $this->error('座位保存失败' . $seatAisle->getError());
				}
			}
		}

		return $this->success('请选择过道', $url);
	}

	/**
	 * 保存单个座位
	 * @param $seatMapId 对应模板的id
	 * @param $seatAiale 座位
	 * @param $url 要跳到的链接
	 * @param $i 行
	 * @param $j 列
	 */
	public function saveSeatAisle($seatMapId, $seatAisle, $i, $j) {
		$seatAisle->x = $i;
		$seatAisle->y = $j;
		$seatAisle->seat_map_id = $seatMapId;
		$seatAisle->create_time = time();
		$seatAisle->update_time = time();
		return $seatAisle->save();
	}

	/**
	 *  过道和座位的切换
	 * 默认为座位，即state = 0; 
	 * 过道state = 1
	 */
	public function isSeat() {
		$Request = Request::instance();
		$id = Request::instance()->param('id/d');
		$SeatAisle = new SeatAisle;
		$SeatAisle = SeatAisle::get($id);

		// 如果是座位则切换为过道
		if($SeatAisle->state === 1) {
			$SeatAisle->state = 0;
		}
		// 反之切换为座位
		else {
			$SeatAisle->state = 1;
		}
		if(!$SeatAisle->save()) {
			$this->error('系统未找到ID为' . $id . '的记录');
		}
		return $this->success('设置成功', $Request->header('referer')); 
	}

	/**
	 * 删除座位模板
	 */
	public function delete() {
		$id = Request::instance()->param('id');
		$seatMap = SeatMap::get($id);

		// 判断当前被删的模板的教室是否正在上课
		if($this->judgeClassroom($id)) {

			//删除对应教师
			if(!$this->deleteClassrooms($seatMap->id)) {
					return $this->error('删除模板对应的教室失败', url('index'));
				}
			 //判断对应座位是否被删除
			 if($this->deleteSeatAisle($id)) {
				$seats = $this->asc();
				$seatMaps = SeatMap::all();
				if(sizeof($seatMaps) != '1') {
					// 如果是最后一个则其前一个座位变为最后一个
					if($seatMap->is_last === 1) {
						$SeatMap = array_reverse($seats);

						$SeatMap[1]->is_last = 1;
						$SeatMap[1]->save();
					}

					// 如果是第一个则其后一个座位变为第一个					
					if($seatMap->is_first === 1) {
						$SeatMap = $seats;
						$SeatMap[1]->is_first = 1;
						$SeatMap[1]->save();
					}
				}
				
				if($seatMap->delete()) {
					return $this->success('删除成功', url('index'));
				}
			}
		}
	}

	/**
	 * 删除模板对应的教室
	 * @param $seatMapId
	*/
	public function deleteClassrooms($seatMapId) {
		$classrooms = Classroom::where('seat_map_id', '=', $seatMapId)->select();
		if(!empty($classrooms)) {
			foreach ($classrooms as $Classroom) {
				$ClassroomController = new ClassroomController;
				if(!$ClassroomController->deleteSeat($Classroom->id) || !$Classroom->delete()) {
					return flase;
				}
			}

		}
		return true;
	}


	/**
	 * 挨个删除座位
	 */
	public function deleteSeatAisle($id) {
		$seatAisles = SeatAisle::where('seat_map_id', '=', $id)->select();
		foreach ($seatAisles as $SeatAisle) {
			if(!$SeatAisle->delete()) {
				return $this->error('模板座位未被删除', url('index'));
			}
		}
		return 1;
	}

	/**
	 * 判断对应当前模板的教室是否在上课
	 * @param $seatMapId 对应模板ID 
	 */
	public function judgeClassroom($seatMapId) {
		$teachers = Teacher::all();
		foreach ($teachers as $Teacher) {
			if($Teacher->classroom_id != 0) {
				$Classroom = Classroom::get($Teacher->classroom_id);
				if($Classroom->seat_map_id === $seatMapId) {
					if($Classroom->out_time < time()) {
						return $this->error('当前有与此模板对应的教室正在上课，请稍后删除', url('index'));
					}
				}
			}
		}
		return true;
	}
}