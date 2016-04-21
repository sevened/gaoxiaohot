$(function () {
    //异步获取新增用户统计数据
	$.post("/admin/summary.new_users",{},function(data){
		if(data){
			//data
			$("#um_new_users6").html(data.um_new_users6);
			$("#um_new_users5").html(data.um_new_users5);
			$("#um_new_users4").html(data.um_new_users4);
			$("#um_new_users3").html(data.um_new_users3);
			$("#um_new_users2").html(data.um_new_users2);
			$("#um_new_users1").html(data.um_new_users1);
			$("#wtb_new_users6").html(data.wtb_new_users6);
			$("#wtb_new_users5").html(data.wtb_new_users5);
			$("#wtb_new_users4").html(data.wtb_new_users4);
			$("#wtb_new_users3").html(data.wtb_new_users3);
			$("#wtb_new_users2").html(data.wtb_new_users2);
			$("#wtb_new_users1").html(data.wtb_new_users1);
			//change
			if(data.um_change1>=0){
				$("#um_change1").html('<i class="fa fa-level-up"></i><span>'+(data.um_change1+"").replace("-","")+'%</span>').addClass("text-navy");
			}else{
				$("#um_change1").html('<i class="fa fa-level-down"></i><span>'+(data.um_change1+"").replace("-","")+'%</span>').addClass("text-danger");
			}
			if(data.um_change2>=0){
				$("#um_change2").html('<i class="fa fa-level-up"></i><span>'+(data.um_change2+"").replace("-","")+'%</span>').addClass("text-navy");
			}else{
				$("#um_change2").html('<i class="fa fa-level-down"></i><span>'+(data.um_change2+"").replace("-","")+'%</span>').addClass("text-danger");
			}
			if(data.um_change3>=0){
				$("#um_change3").html('<i class="fa fa-level-up"></i><span>'+(data.um_change3+"").replace("-","")+'%</span>').addClass("text-navy");
			}else{
				$("#um_change3").html('<i class="fa fa-level-down"></i><span>'+(data.um_change3+"").replace("-","")+'%</span>').addClass("text-danger");
			}
			if(data.um_change4>=0){
				$("#um_change4").html('<i class="fa fa-level-up"></i><span>'+(data.um_change4+"").replace("-","")+'%</span>').addClass("text-navy");
			}else{
				$("#um_change4").html('<i class="fa fa-level-down"></i><span>'+(data.um_change4+"").replace("-","")+'%</span>').addClass("text-danger");
			}
			if(data.um_change5>=0){
				$("#um_change5").html('<i class="fa fa-level-up"></i><span>'+(data.um_change5+"").replace("-","")+'%</span>').addClass("text-navy");
			}else{
				$("#um_change5").html('<i class="fa fa-level-down"></i><span>'+(data.um_change5+"").replace("-","")+'%</span>').addClass("text-danger");
			}
			if(data.wtb_change1>=0){
				$("#wtb_change1").html('<i class="fa fa-level-up"></i><span>'+(data.wtb_change1+"").replace("-","")+'%</span>').addClass("text-navy");
			}else{
				$("#wtb_change1").html('<i class="fa fa-level-down"></i><span>'+(data.wtb_change1+"").replace("-","")+'%</span>').addClass("text-danger");
			}
			if(data.wtb_change2>=0){
				$("#wtb_change2").html('<i class="fa fa-level-up"></i><span>'+(data.wtb_change2+"").replace("-","")+'%</span>').addClass("text-navy");
			}else{
				$("#wtb_change2").html('<i class="fa fa-level-down"></i><span>'+(data.wtb_change2+"").replace("-","")+'%</span>').addClass("text-danger");
			}
			if(data.wtb_change3>=0){
				$("#wtb_change3").html('<i class="fa fa-level-up"></i><span>'+(data.wtb_change3+"").replace("-","")+'%</span>').addClass("text-navy");
			}else{
				$("#wtb_change3").html('<i class="fa fa-level-down"></i><span>'+(data.wtb_change3+"").replace("-","")+'%</span>').addClass("text-danger");
			}
			if(data.wtb_change4>=0){
				$("#wtb_change4").html('<i class="fa fa-level-up"></i><span>'+(data.wtb_change4+"").replace("-","")+'%</span>').addClass("text-navy");
			}else{
				$("#wtb_change4").html('<i class="fa fa-level-down"></i><span>'+(data.wtb_change4+"").replace("-","")+'%</span>').addClass("text-danger");
			}
			if(data.wtb_change5>=0){
				$("#wtb_change5").html('<i class="fa fa-level-up"></i><span>'+(data.wtb_change5+"").replace("-","")+'%</span>').addClass("text-navy");
			}else{
				$("#wtb_change5").html('<i class="fa fa-level-down"></i><span>'+(data.wtb_change5+"").replace("-","")+'%</span>').addClass("text-danger");
			}
			//获取5天内用户数据
			var lineChart = echarts.init(document.getElementById("echarts-line-chart"));
		    var lineoption = {
		        title : {
		            text: ''
		        },
		        tooltip : {
		            trigger: 'axis'
		        },
		        grid : {
		        	x:30,
		        	y:30,
		        	x2:30,
		        	y2:50
		        },
		        legend: {
		            data:['U盟','无图标']
		        },
		        calculable : true,
		        xAxis : [
		            {
		                type : 'category',
		                boundaryGap : false,
		                data : ['2015-07-19','2015-07-20','2015-07-21','2015-07-22','2015-07-23','2015-07-24',]
		            }
		        ],
		        yAxis : [
		            {
		                type : 'value',
		                axisLabel : {
		                    formatter: ''
		                }
		            }
		        ],
		        series : [
		            {
		                name:'U盟',
		                type:'line',
		                data:[15, 13, 12, 13, 10],
		                markPoint : {
		                    data : [
		                        {type : 'max', name: '最大值'},
		                        {type : 'min', name: '最小值'}
		                    ]
		                },
		                markLine : {
		                    data : [
		                        {type : 'average', name: '平均值'}
		                    ]
		                }
		            },
		            {
		                name:'无图标',
		                type:'line',
		                data:[data.wtb_five_line],
		                markPoint : {
		                    data : [
		                        {type : 'max', name: '最大值'},
		                        {type : 'min', name: '最小值'}
		                    ]
		                },
		                markLine : {
		                    data : [
		                        {type : 'average', name : '平均值'}
		                    ]
		                }
		            }
		        ]
		    };
		    lineChart.setOption(lineoption); 
		}
	},'json');

	//获取数据汇总
	$.post("/admin/summary.base_data",{},function(data){
		if(data){
			var table_str = '';
			$.each(data.um_base_data, function(i, item){
			   table_str += '<tr class="gradeA"><td></td><td>'+item.name+'</td><td>'+item.today_new_users+'</td><td></td><td>'+item.yesterday_new_users+'</td><td></td><td></td></tr>';
			});
			$("#data_base").html(table_str);
		}
	},'json');
	
});
