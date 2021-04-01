function makeChart(page) {
	//assign variables
	console.log('*** Totals Var ***')
	console.log(total)
	console.log('*** End Totals Var ***')

	var start_month = Number(page.filters['date'][0].slice(4,6));
	var end_month = Number(page.filters['date'][1].slice(4,6));
	var start_year = Number(page.filters['date'][0].slice(0,4));
	var end_year = Number(page.filters['date'][1].slice(0,4));
	var categories = Array();
	var pieData = Array();
	var column_data = Array();
	var column_data_final = Array();
	var column_data_input = Array();
		column_data_final[0] = [];
		column_data_final[1] = [];
		column_data_final[2] = [];

	//assign y axis label based on page.dataType
	if (page.dataType === "watch") {
		column_data_final[0].push("# of Watches")
		column_data_final[1].push("Tornado Watches")
		column_data_final[2].push("Severe Thunderstorm Watches")
	} else if (page.dataType === "report") {
		column_data_final[3] = [];
		column_data_final[0].push("Total Reports");
		column_data_final[1].push("Tornado Reports");
		column_data_final[2].push("Hail Reports");
		column_data_final[3].push("All Wind Reports");
	}

//assign column_data and categories based on whether the chart type is month or year
if (page.chartType === "month") {
	if (page.filters['date'][1].slice(0,4) === page.filters['date'][0].slice(0,4) ) {
		for (i=start_month; i<(end_month +1); i++) {
			categories[i-start_month] = month_abbrev[i-1];
			if (page.dataType === "watch") {
				column_data_final[0].push(total['time']['month']['total'][i]);
				column_data_final[1].push(total['time']['month']['TOR'][i]);
				column_data_final[2].push(total['time']['month']['SVR'][i]);
			} else {
				column_data_final[0].push(total['time']['month']['total'][i]);
				column_data_final[1].push(total['time']['month']['T'][i]);
				column_data_final[2].push(total['time']['month']['A'][i]);	
				column_data_final[3].push(total['time']['month']['ALLW'][i]);
			}
		}
	} else {
		categories = month_abbrev;
		for (i=1; i<13; i++){
			if (page.dataType === "watch") {
				column_data_final[0].push(total['time']['month']['total'][i]);
				column_data_final[1].push(total['time']['month']['TOR'][i]);
				column_data_final[2].push(total['time']['month']['SVR'][i]);
			} else {
				column_data_final[0].push(total['time']['month']['total'][i]);
				column_data_final[1].push(total['time']['month']['T'][i]);
				column_data_final[2].push(total['time']['month']['A'][i]);	
				column_data_final[3].push(total['time']['month']['ALLW'][i]);				
			}
		}
	}
} else if (page.chartType === "year") {
		for (i=start_year; i<(end_year +1); i++) {
			categories[i-start_year] = year_list[i-2000];
			if (page.dataType === "watch") {
				column_data_final[0].push(total['time']['year']['total'][i]);
				column_data_final[1].push(total['time']['year']['TOR'][i]);
				column_data_final[2].push(total['time']['year']['SVR'][i]);
			} else {
				column_data_final[0].push(total['time']['year']['total'][i]);
				column_data_final[1].push(total['time']['year']['T'][i]);
				column_data_final[2].push(total['time']['year']['A'][i]);	
				column_data_final[3].push(total['time']['year']['ALLW'][i]);			
			}
		}
} else if (page.chartType === "type" || page.chartType === "pie") {
		if (page.dataType ==="watch") {
			column_data_final[0].push(total['type'][1]);
			column_data_final[0].push(total['type'][2]);
			column_data_final[0].push(total['type'][3]);
			column_data_final[0].push(total['type'][4]);
			pieData[0] = [totalTitle[1],total['type'][1]];
			pieData[1] = [totalTitle[2],total['type'][2]];
			pieData[2] = [totalTitle[3],total['type'][3]];
			pieData[3] = [totalTitle[4],total['type'][4]];
		} else {	
			if (page.reportType === "ALL") {
					column_data_final[0].push(total['type'][1]);
					column_data_final[0].push(total['type'][2]);
					column_data_final[0].push(total['type'][3]);
					column_data_final[0].push(total['type'][4]);
				for (i=1; i<(total['type'].length); i++) {
					pieData[i-1] = [totalTitle[i],total['type'][i]];
				}
			} else {
					column_data_final[0] = [];
					column_data_final[0].push("Total Reports");
				for (i=1; i<(total['mag'].length); i++) {
					column_data_final[0].push(total['mag'][i]);
					pieData[i-1] = [totalTitle[i],total['mag'][i]];
				}
			}
		}
		for (i=0; i<(totalTitle.length-1); i++) {
		categories[i] = totalTitle[i+1];
		}
}

function randomTen(max) {
	var arr = [];
		while(arr.length < 10){
			var r = Math.floor(Math.random() * max);
			arr.push(r);
		}
	return arr;
} 



if (page.chartType === "pie") {

	var chart = c3.generate({
		bindto: '#chart',
		data: {
        	columns: pieData,
        	type : 'pie'
   		}
	});

} else if (page.chartType === "stanford") {

	let new_flat_day = []
	let new_flat_hr = []
	let new_flat_ct = []

	// Loop through nested objects to add to arrays for stanford chart
	Object.keys(total['stanford']['unformat']).forEach(key => {
		Object.keys(total['stanford']['unformat'][key]).forEach(innerKey => {
			new_flat_day.push(Number(key))
			new_flat_hr.push(Number(innerKey))
			new_flat_ct.push(total['stanford']['unformat'][key][innerKey])
		})
	})

	//console.log(new_flat_ct,new_flat_day,new_flat_hr)

	let ct_max = Math.max(...new_flat_ct)
	let day_min = Math.min(...new_flat_day)
	if (day_min < 32) { day_min = 1}
	let day_max = Math.max(...new_flat_day)
	if (day_max > 334) { day_max = 366}

	// Prep the arrays
	let flat_day = ["Day",...new_flat_day]
	let flat_hr = ["Hour",...new_flat_hr]
	let flat_ct = ["Count",...new_flat_ct]

	// Calculate point radius
	let pt_r = 4
	if (day_max-day_min < 120) { pt_r = 6 }

	var chart = c3.generate({
		bindto: '#chart',
		data: {
			x: 'Day',
        	epochs: 'Count',
			columns: [flat_day,flat_hr,flat_ct],
			type: 'stanford'
		},
		legend: {
			hide: true
		},
		point: {
			focus: {
				expand: {
					r: 10
				}
			},
			r: pt_r
		},
		axis: {
			x: {
				show: true,
				label: {
					text: 'Day',
					position: 'outer-center'
				},
				min: day_min,
				max: day_max,
				tick: {
					values: d3.range(day_min, day_max, Math.ceil((day_max - day_min)/24)),
					format: function(x) { 

						let date_obj = d3.timeParse('%j %Y')(`${x} 2000`)
						let date_str = d3.timeFormat('%b %d')(date_obj)

						return date_str
					}
				},
				padding: {
					top: 0,
					bottom: 0,
					left: 0,
					right: 0
				},
			},
			y: {
				show: true,
				label: {
					text: 'Hour',
					position: 'outer-middle'
				},
				min: 0,
				max: 23,
				tick: {
					values: d3.range(0, 23, 1)
				},
				padding: {
					top: 5,
					bottom: 0,
					left: 0,
					right: 0
				},
			}
		},
		stanford: {
			scaleMin: 1,
			scaleMax: 150,
			colors: d3.interpolateReds,
			scaleValues: () => {return [1,5,10,20,50,100,150]},
			// scaleValues: () => {
				
			// 	let inc = Math.floor(ct_max/10)
			// 	if (inc == 0) { inc = 1 }
			// 	if (inc > 10) { inc = 20 }
			// 	let vals = []
			// 	for (i=1;i*inc<ct_max;i++) {
			// 		vals.push(inc*i)
			// 	}
			// 	return vals
			// },
			padding: {
				top: 15,
				right: 15,
				bottom: 0,
				left: 0
			}
		}
	});

} else if (page.watchType != "ALL") {
	column_data_input = column_data_final[0];
	var chart = c3.generate({
		bindto: '#chart',
		data: {
			columns: 
				[column_data_input],
			type: 'bar'
		},
		bar: {
			width: {
				ratio: 0.8
			}
		},
		axis: {
			x: {
				type: 'category',
				categories: categories
			},
			y: {
				label: {
					text: column_data_input[0],
					position: 'outer-middle'
				}
			}
		}
	});
} else if (page.reportType != "ALL") {
	column_data_input = column_data_final[0];
	var chart = c3.generate({
		bindto: '#chart',
		data: {
			columns: 
				[column_data_input],
			type: 'bar'
		},
		bar: {
			width: {
				ratio: 0.8
			}
		},
		axis: {
			x: {
				type: 'category',
				categories: categories
			},
			y: {
				label: {
					text: column_data_input[0],
					position: 'outer-middle'
				}
			}
		}
	});
} else if ((page.watchType === "ALL" || page.reportType === "ALL") && (page.chartType === "month" || page.chartType === "year")) {
	column_data_input = column_data_final;
	var chart = c3.generate({
		bindto: '#chart',
		data: {
			columns: 
				column_data_input,
			type: 'bar'
		},
		bar: {
			width: {
				ratio: 0.8
			}
		},
		axis: {
			x: {
				type: 'category',
				categories: categories
			},
			y: {
				label: {
					text: column_data_input[0][0],
					position: 'outer-middle'
				}
			}
		}
	});
} else {
	column_data_input = column_data_final[0];
	console.log('should be here')
	var chart = c3.generate({
		bindto: '#chart',
		data: {
			columns: 
				[column_data_input],
			type: 'bar'
		},
		bar: {
			width: {
				ratio: 0.8
			}
		},
		axis: {
			x: {
				type: 'category',
				categories: categories
			},
			y: {
				label: {
					text: column_data_input[0],
					position: 'outer-middle'
				}
			}
		}
	});
}

}//end of makeChart function