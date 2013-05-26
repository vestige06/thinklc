var BaiduMap = function() {
	var _map, _pointBase, _results;
	_loadMap = function() {
		var compmap = ____gongsi.compmap.split(',');
		var map_lng = Number(compmap[0]);
		var map_lat = Number(compmap[1]);
		_pointBase = new BMap.Point( map_lng, map_lat );
			   
		//加载地图
		_map.centerAndZoom(_pointBase, 16);
		_map.addControl(new BMap.NavigationControl());
		_map.enableScrollWheelZoom();
		_addicon();
	},
	_addicon = function(type) {
		// 编写自定义函数,创建标注
		var myIcon = new BMap.Icon(skinurl+"/Images/marked.gif", new BMap.Size(26, 37));
		var marker = new BMap.Marker(_pointBase, {
			enableMassClear: false,
			icon: myIcon
		});
		var label = new BMap.Label(____gongsi.address, {offset: new BMap.Size(30, -5)});
		marker.setLabel(label);
		marker.disableMassClear();
		_map.addOverlay(marker);
	},
	//公交搜索结果
	_busresult = function(results) {
		_map.clearOverlays();
		var start = results.getStart();
		var end = results.getEnd();
		if (!start || !end) {
			$("#map_search_result").html('');
			alert("查询不到公交线路，请重新输入公交/地铁站点名称。");
			return;
		}
	},
	//驾车搜索结果
	_carresult = function(results) {
		_map.clearOverlays();
		var start = results.getStart();
		var end = results.getEnd();
		if (!start || !end) {
			$("#map_search_result").html('');
			alert("查询不到驾车线路，您可以尝试将起点或终点修改为附近的标志性建筑或道路名。");
			return;
		}
	},

	this.search = function() {
		var start = $("#map_route_from").val();
		var end = $("#map_route_to").val();
		if (!start || !end) {
			alert("请先输入起点。");
			$("#map_route_from").focus();
			return;
		}
		if (document.getElementById("map_route_bus").checked) {
			var transit = new BMap.TransitRoute(_map, {
				renderOptions: {map: _map, panel: "map_search_result", autoViewport: true}, 
				policy: BMAP_TRANSIT_POLICY_LEAST_TIME,
				onSearchComplete: _busresult
			});  
			transit.search(start, _pointBase);
		} else {
			var transit = new BMap.DrivingRoute(_map, {
				renderOptions: {map: _map, panel: "map_search_result", autoViewport: true}, 
				policy: BMAP_DRIVING_POLICY_LEAST_TIME,
				onSearchComplete: _carresult
			});   
			transit.search(start, end);
		}
	};
	this.init = function() {
		_map = new BMap.Map("map"); //初始化地图
		_loadMap();
	};
}
var map = new BaiduMap();
map.init();