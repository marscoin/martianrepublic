$(function () {

	var data, chartOptions

	data = [
		{ label: "Global Marscoin", data: Math.floor (Math.random() * 100 + 250) }, 
		{ label: "Your holdings", data: Math.floor (Math.random() * 100 + 350) }
	]

	chartOptions = {		
		series: {
			pie: {
				show: true,  
				innerRadius: 0, 
				stroke: {
					width: 4
				}
			}
		}, 
		legend: {
			show: false,
			position: 'ne'
		}, 
		tooltip: true,
		tooltipOpts: {
			content: '%s: %y'
		},
		grid: {
			hoverable: true
		},
		colors: mvpready_core.layoutColors
	}

	var holder = $('#pie-chart')

	if (holder.length) {
		$.plot(holder, data, chartOptions )
	}


})