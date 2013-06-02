$(function(){
	// var a = mapbox.auto('map', 'goodjessdev.map-qavt269q');
	// var a = mapbox.auto('map', 'goodjessdev.map-p3lf1ark');
	var a = L.mapbox.map('map', 'goodjessdev.map-p3lf1ark');
	a.addControl(L.mapbox.geocoderControl('goodjessdev.map-p3lf1ark'));
});
