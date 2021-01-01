<?php
namespace app\index\controller;
use think\Controller;
use app\common\Classroom;
use think\Request;
use think\validate;
use app\common\model\SeatMap;
use app\common\model\Course;
use app\common\model\SeatAisle;
/**
 * 座位图模板
 */
class SeatMapController extends Controller {
	public function index(){
		$seatMapAsc = SeatMap::order('id')->select();
		$seatMapDesc = SeatMap::order('id desc')->select();
		$id = Request::instance()->param('id');
		
		// 若是最后一个则下一个模板为最开始的模板
		if($id == -2 || is_null($id)) {
			$seatmap = $seatMapAsc[0];
			$id = $seatmap->id;
		}
		// 若是第一个模板则上一个模板是最后的模板
		if($id == -1) {
			
			$seatmap = $seatMapDesc[0];
			$id = $seatmap->id;
		}
		$course_id = Request::instance()->param('course_id');
		// 实例化课程
		$Course = Course::get($course_id);
		$SeatMap = SeatMap::get($id);
		$seatAisle = new SeatAisle;
		$seatAisle = SeatAisle::where('seat_map_id', '=', $id)->select();
		$this->assign('seatMap1', $seatMapAsc);
		$this->assign('seatMap2', $seatMapDesc);
		$this->assign('SeatMap',$SeatMap);
		$this->assign('seatAisles', $seatAisle);
		$this->assign('Course',$Course);
		return $this->fetch();

	}
	/**
	 * 返回数据库里面id升序的数组
	 */
	public function asc() {
		return(SeatMap::order('id')->select());
		
	}
	/**
	 * 返回数据库id降序的数组
	 */
	public function desc() {
		return(SeatMap::order('id desc')->select());
	}
	/**
	 * 增加模板
	 */
	public function add(){

		return $this->fetch();

	}
	/**
	 * 编辑模板座位图的过道以及座位
	 * 设置每个座位是过道还是座位
	 */
	public function edit() {
		$id = Request::instance()->param('id/d');
		$SeatAisle = new SeatAisle;
		$SeatAisle = SeatAisle::where('seat_map_id', '=', $id)->select();
		$this->assign('seatAisles', $SeatAisle);
		// dump($SeatAisle);
		// die();
		$SeatMap = new SeatMap;
		$SeatMap = SeatMap::get($id);
		$this->assign('SeatMap', $SeatMap);
		return $this->fetch();
	}
	/**
	 * 保存模板的行和列
	 */
	public function save() {
		$SeatMap = new SeatMap;
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
		if($SeatAisle->state == "1") {

			$SeatAisle->state = "0";
		}
		// 反之切换为座位
		else {

			$SeatAisle->state = "1";
		}
		if(!$SeatAisle->save()) {
			$this->error('系统未找到ID为' . $id . '的记录');
		}
		return $this->success('设置成功', $Request->header('referer')); 
	}

	public function template1(){

		return $this->fetch();

	}
	public function template2(){

		return  $this->fetch();

	}
	/**
	 * 删除座位模板
	 */
	public function delete() {
		$id = Request::instance()->param('id');
		if($this->DeleteSeatAisle($id)) {
			$seatMap = SeatMap::get($id);

			// 如果是最后一个则其前一个座位变为最后一个
			if($seatMap->is_last === 1) {
				$SeatMap = $this->desc();

				$SeatMap[1]->is_last = 1;
				$SeatMap[1]->save();
			}


			// 如果是第一个则其后一个座位变为第一个
			if($seatMap->is_first === 1) {
				$SeatMap = $this->asc();
				$SeatMap[1]->is_first = 1;
				$SeatMap[1]->save();
			}
			if($seatMap->delete()) {
				return $this->success('删除成功', url('index'));
			}
		}
	}
	/**
	 * 挨个删除座位
	 */
	public function DeleteSeatAisle($id) {
		$seatAisles = SeatAisle::where('seat_map_id', '=', $id)->select();
		foreach ($seatAisles as $seatAisle) {
			$seatAisle->delete();
		}
		return 1;
	}
}