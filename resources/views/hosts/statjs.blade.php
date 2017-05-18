<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/5/17
 * Time: 10:34
 */
?>

<script src="<?= url('js/echarts.min.js') ?>"></script>
<script>
    // 基于准备好的dom，初始化echarts实例
    var myChart1 = echarts.init(document.getElementById('box1'));
    var myChart2 = echarts.init(document.getElementById('box2'));

    $(window).on('resize',function(){
        // 调用相关echarts的resize方法. ** 放在echart声明之后
        myChart1.resize();
        myChart2.resize();
    });

    option1 = {
        title : {
            text: '服务统计',
            //subtext: '纯属虚构',
            x:'center',
            textStyle: {
                color: '#fff'
            }
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            //orient: 'vertical',
            //left: 'left',
            //data: ['正常','异常','新增']
        },
        color:['#c0242a','#b6c434','#fccf0f','#ec7c24','#28727b','#ff8463'],
        backgroundColor: '#323c48',//'#404a59',
        series : [
            {
                name: '服务统计',
                type: 'pie',
                selectedMode: 'single',
                radius : '55%',
                center: ['50%', '60%'],
                data: <?= $ServiceRatio ?>,
                /*data:[
                 {value:535, name:'正常'},
                 {value:310, name:'异常'},
                 {value:234, name:'新增'}
                 ],*/
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    option2 = {
        title : {
            text: '系统占比',
            //subtext: '纯属虚构',
            x:'center',
            textStyle: {
                color: '#fff'
            }
        },
        tooltip : {
            trigger: 'item',
            formatter: "{a} <br/>{b} : {c} ({d}%)"
        },
        legend: {
            //orient: 'vertical',
            //left: 'left',
            //data: ['Linux/Unix系统','Windows系统','其他']
        },
        color:['#9bcb62','#f3a43b','#60c0de','#974da9','#b6c434','#ff8463'],
        backgroundColor: '#323c48',//'#404a59',
        series : [
            {
                name: '系统占比',
                type: 'pie',
                selectedMode: 'single',
                radius : '55%',
                center: ['50%', '60%'],
                data: <?= $OSRatio ?>,
                /*data:[
                 {value:535, name:'Linux/Unix系统'},
                 {value:310, name:'Windows系统'},
                 {value:234, name:'其他'}
                 ],*/
                itemStyle: {
                    emphasis: {
                        shadowBlur: 10,
                        shadowOffsetX: 0,
                        shadowColor: 'rgba(0, 0, 0, 0.5)'
                    }
                }
            }
        ]
    };

    myChart1.setOption(option1);
    myChart2.setOption(option2);
</script>