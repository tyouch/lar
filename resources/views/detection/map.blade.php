<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/4/26
 * Time: 14:44
 */
?>
@extends('layouts.app')
@section('title', '入侵检测')


@section('content')
    <div id="main" style="height: 810px; margin-top: -10px;"></div>
    <div class="layer2">
        <table class="table"><!--table-bordered table-hover-->
            <tr>
                <th>入侵发现时间</th>
                <th>入侵结束时间</th>
                <th>源地址</th>
                <th>目标地址</th>
                <th>攻击次数</th>
            </tr>
        </table>
    </div>
    @includeIf('public.nav', [])
    @includeIf('public.time', [])
@endsection


@push('scripts')
<script src="<?= url('js/echarts.min.js') ?>"></script>
<script>

    // 加载列表方法
    function loadList() {
        $.ajax({
            url         : '<?= url('detection/map') ?>',
            type        : 'GET',
            data        :{
                'csrf-token'    : '{{ csrf_token() }}',
                'test'          : true
            },
            dataType    : 'JSON',
            headers     : {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            success     : function (d, s) {
                console.log('[' + s +']', d);
                if(s == 'success'){
                    $(".layer2").html(
                        '<table class="table">' +
                        '<tr>' +
                        '<th>入侵发现时间</th>' +
                        '<th>入侵结束时间</th>' +
                        '<th>源地址</th>' +
                        '<th>目标地址</th>' +
                        '<th>攻击次数</th>' +
                        '</tr>' +
                        '</table>'
                    );
                    $.each(d.data, function (index, content) {
                        $(".table").append(
                            '<tr class="tra" data-id="' + content.plugin_sid + '">' +
                            '<td>' + content.time1 + '</td>' +
                            '<td>' + content.time2 + '</td>' +
                            '<td>' + content.ip_src + '</td>' +
                            '<td>' + content.ip_dst + '</td>' +
                            '<td>' + content.att_times + '</td>' +
                            '</tr>'
                        );
                    });
                }
            },
            /*complete    : function (d, s) {
             console.log('[['+s+']]');
             console.log(d);
             }*/
        });
    }

    var myChart = echarts.init(document.getElementById('main'));
    $(function () {
        $(".tra").click(function (e) {
            //alert($(this).attr('data-id'));
            location.href = '';
        });

        $(window).on('resize', function () {
            // 调用相关echarts的resize方法. ** 放在echart声明之后
            myChart.resize();
        });

        //---------------- 动态刷新列表 ---------------
        var timer1 = setInterval(loadList, 5000);
        //--------------------------------------------

    });

    myChart.showLoading();
    $.get("<?= url('js/world.json') ?>", function (worldJson) {
        myChart.hideLoading();
        echarts.registerMap('world', worldJson); // 注册

        var nameMap = {
            'Afghanistan':'阿富汗',
            'Angola':'安哥拉',
            'Albania':'阿尔巴尼亚',
            'United Arab Emirates':'阿联酋',
            'Argentina':'阿根廷',
            'Armenia':'亚美尼亚',
            'French Southern and Antarctic Lands':'法属南半球和南极领地',
            'Australia':'澳大利亚',
            'Austria':'奥地利',
            'Azerbaijan':'阿塞拜疆',
            'Burundi':'布隆迪',
            'Belgium':'比利时',
            'Benin':'贝宁',
            'Burkina Faso':'布基纳法索',
            'Bangladesh':'孟加拉国',
            'Bulgaria':'保加利亚',
            'The Bahamas':'巴哈马',
            'Bosnia and Herzegovina':'波斯尼亚和黑塞哥维那',
            'Belarus':'白俄罗斯',
            'Belize':'伯利兹',
            'Bermuda':'百慕大',
            'Bolivia':'玻利维亚',
            'Brazil':'巴西',
            'Brunei':'文莱',
            'Bhutan':'不丹',
            'Botswana':'博茨瓦纳',
            'Central African Republic':'中非共和国',
            'Canada':'加拿大',
            'Switzerland':'瑞士',
            'Chile':'智利',
            'China':'中国',
            'Ivory Coast':'象牙海岸',
            'Cameroon':'喀麦隆',
            'Democratic Republic of the Congo':'刚果民主共和国',
            'Republic of the Congo':'刚果共和国',
            'Colombia':'哥伦比亚',
            'Costa Rica':'哥斯达黎加',
            'Cuba':'古巴',
            'Northern Cyprus':'北塞浦路斯',
            'Cyprus':'塞浦路斯',
            'Czech Republic':'捷克共和国',
            'Germany':'德国',
            'Djibouti':'吉布提',
            'Denmark':'丹麦',
            'Dominican Republic':'多明尼加共和国',
            'Algeria':'阿尔及利亚',
            'Ecuador':'厄瓜多尔',
            'Egypt':'埃及',
            'Eritrea':'厄立特里亚',
            'Spain':'西班牙',
            'Estonia':'爱沙尼亚',
            'Ethiopia':'埃塞俄比亚',
            'Finland':'芬兰',
            'Fiji':'斐',
            'Falkland Islands':'福克兰群岛',
            'France':'法国',
            'Gabon':'加蓬',
            'United Kingdom':'英国',
            'Georgia':'格鲁吉亚',
            'Ghana':'加纳',
            'Guinea':'几内亚',
            'Gambia':'冈比亚',
            'Guinea Bissau':'几内亚比绍',
            'Equatorial Guinea':'赤道几内亚',
            'Greece':'希腊',
            'Greenland':'格陵兰',
            'Guatemala':'危地马拉',
            'French Guiana':'法属圭亚那',
            'Guyana':'圭亚那',
            'Honduras':'洪都拉斯',
            'Croatia':'克罗地亚',
            'Haiti':'海地',
            'Hungary':'匈牙利',
            'Indonesia':'印尼',
            'India':'印度',
            'Ireland':'爱尔兰',
            'Iran':'伊朗',
            'Iraq':'伊拉克',
            'Iceland':'冰岛',
            'Israel':'以色列',
            'Italy':'意大利',
            'Jamaica':'牙买加',
            'Jordan':'约旦',
            'Japan':'日本',
            'Kazakhstan':'哈萨克斯坦',
            'Kenya':'肯尼亚',
            'Kyrgyzstan':'吉尔吉斯斯坦',
            'Cambodia':'柬埔寨',
            'South Korea':'韩国',
            'Kosovo':'科索沃',
            'Kuwait':'科威特',
            'Laos':'老挝',
            'Lebanon':'黎巴嫩',
            'Liberia':'利比里亚',
            'Libya':'利比亚',
            'Sri Lanka':'斯里兰卡',
            'Lesotho':'莱索托',
            'Lithuania':'立陶宛',
            'Luxembourg':'卢森堡',
            'Latvia':'拉脱维亚',
            'Morocco':'摩洛哥',
            'Moldova':'摩尔多瓦',
            'Madagascar':'马达加斯加',
            'Mexico':'墨西哥',
            'Macedonia':'马其顿',
            'Mali':'马里',
            'Myanmar':'缅甸',
            'Montenegro':'黑山',
            'Mongolia':'蒙古',
            'Mozambique':'莫桑比克',
            'Mauritania':'毛里塔尼亚',
            'Malawi':'马拉维',
            'Malaysia':'马来西亚',
            'Namibia':'纳米比亚',
            'New Caledonia':'新喀里多尼亚',
            'Niger':'尼日尔',
            'Nigeria':'尼日利亚',
            'Nicaragua':'尼加拉瓜',
            'Netherlands':'荷兰',
            'Norway':'挪威',
            'Nepal':'尼泊尔',
            'New Zealand':'新西兰',
            'Oman':'阿曼',
            'Pakistan':'巴基斯坦',
            'Panama':'巴拿马',
            'Peru':'秘鲁',
            'Philippines':'菲律宾',
            'Papua New Guinea':'巴布亚新几内亚',
            'Poland':'波兰',
            'Puerto Rico':'波多黎各',
            'North Korea':'北朝鲜',
            'Portugal':'葡萄牙',
            'Paraguay':'巴拉圭',
            'Qatar':'卡塔尔',
            'Romania':'罗马尼亚',
            'Russia':'俄罗斯',
            'Rwanda':'卢旺达',
            'Western Sahara':'西撒哈拉',
            'Saudi Arabia':'沙特阿拉伯',
            'Sudan':'苏丹',
            'South Sudan':'南苏丹',
            'Senegal':'塞内加尔',
            'Solomon Islands':'所罗门群岛',
            'Sierra Leone':'塞拉利昂',
            'El Salvador':'萨尔瓦多',
            'Somaliland':'索马里兰',
            'Somalia':'索马里',
            'Republic of Serbia':'塞尔维亚共和国',
            'Suriname':'苏里南',
            'Slovakia':'斯洛伐克',
            'Slovenia':'斯洛文尼亚',
            'Sweden':'瑞典',
            'Swaziland':'斯威士兰',
            'Syria':'叙利亚',
            'Chad':'乍得',
            'Togo':'多哥',
            'Thailand':'泰国',
            'Tajikistan':'塔吉克斯坦',
            'Turkmenistan':'土库曼斯坦',
            'East Timor':'东帝汶',
            'Trinidad and Tobago':'特里尼达和多巴哥',
            'Tunisia':'突尼斯',
            'Turkey':'土耳其',
            'United Republic of Tanzania':'坦桑尼亚联合共和国',
            'Uganda':'乌干达',
            'Ukraine':'乌克兰',
            'Uruguay':'乌拉圭',
            'United States of America':'美国',
            'Uzbekistan':'乌兹别克斯坦',
            'Venezuela':'委内瑞拉',
            'Vietnam':'越南',
            'Vanuatu':'瓦努阿图',
            'West Bank':'西岸',
            'Yemen':'也门',
            'South Africa':'南非',
            'Zambia':'赞比亚',
            'Zimbabwe':'津巴布韦'
        };

        /*var geoCoordMap = {
         '郑州': [113.4668, 34.6234],
         '华盛顿': [-77.0214, 38.5355],
         '渥太华': [-73.58, 45.20],
         '里约热内卢': [-43.15, -22.54],
         '东京': [139.44, 35.41],
         '平壤': [125.47, 39],
         '首尔': [127.03, 37.35],
         '柏林': [13.2, 52.31],
         '莫斯科': [37.37, 55.45],
         '利雅得': [46.44, 24.39],
         '新德里': [77.13, 28.37],
         '马尼拉': [121, 14.37],
         '雅加达': [106.45, -6.08],
         '河内': [105.53, 21.01],
         '悉尼': [151.17, -33.55],
         };

         var ZZData = [
         [{name: '郑州'}, {name: '华盛顿', value: 85}],
         [{name: '郑州'}, {name: '渥太华', value: 85}],
         [{name: '郑州'}, {name: '里约热内卢', value: 85}],
         [{name: '郑州'}, {name: '东京', value: 55}],
         [{name: '郑州'}, {name: '平壤', value: 10}],
         [{name: '郑州'}, {name: '首尔', value: 10}],
         [{name: '郑州'}, {name: '柏林', value: 50}],
         [{name: '郑州'}, {name: '莫斯科', value: 85}],
         [{name: '郑州'}, {name: '利雅得', value: 65}],
         [{name: '郑州'}, {name: '新德里', value: 85}],
         [{name: '郑州'}, {name: '马尼拉', value: 40}],
         [{name: '郑州'}, {name: '雅加达', value: 85}],
         [{name: '郑州'}, {name: '河内', value: 10}],
         [{name: '郑州'}, {name: '悉尼', value: 65}],
         ];*/

        var geoCoordMap = <?= $geoCoordMap ?>;
        var ZZData = <?= $zzData ?>;

        // var planePath = 'path://M1705.06,1318.313v-89.254l-319.9-221.799l0.073-208.063c0.521-84.662-26.629-121.796-63.961-121.491c-37.332-0.305-64.482,36.829-63.961,121.491l0.073,208.063l-319.9,221.799v89.254l330.343-157.288l12.238,241.308l-134.449,92.931l0.531,42.034l175.125-42.917l175.125,42.917l0.531-42.034l-134.449-92.931l12.238-241.308L1705.06,1318.313z';

        // ZZData 转换 [{fromName:'xxx', toName:'xxx', coords:[xx, xx]},{...}]
        var convertData = function (data) {
            var res = [];
            for (var i = 0; i < data.length; i++) {
                var dataItem = data[i];
                var fromCoord = geoCoordMap[dataItem[1].name]; // 方向
                var toCoord = geoCoordMap[dataItem[0].name]; // 方向
                if (fromCoord && toCoord) {
                    res.push({
                        fromName: dataItem[1].name, // 方向
                        toName: dataItem[0].name, // 方向
                        coords: [fromCoord, toCoord],
                        ip_src: dataItem[1].ip_src,
                        ip_dst: dataItem[1].ip_dst,
                        time1: dataItem[1].time1,
                        time2: dataItem[1].time2,
                        att_times: dataItem[1].att_times
                    });
                }
            }
            return res;
        };

        var color = ['#ffa022', '#a6c84c', '#46bee9'];
        var series = [{ // 涟漪圈圈 河南 单独
            name: 'Beijing',
            type: 'effectScatter',
            coordinateSystem: 'geo',
            zlevel: 2,
            rippleEffect: {
                brushType: 'stroke'
            },
            label: {
                normal: {
                    show: true,
                    position: 'right',
                    formatter: '{b}'
                }
            },
            //symbol: 'roundRect', // pin diamond
            symbolSize: 15,
            itemStyle: {
                normal: {
                    color: color[1]
                }
            },
            data: [{
                name: 'Beijing',
                value: geoCoordMap.Beijing
            }]

        }];
        [['Beijing', ZZData]].forEach(function (item, i) {
            series.push(
                { // 白线轨迹
                    name: item[0] + ' Beijing',
                    type: 'lines',
                    zlevel: 1,
                    effect: {
                        show: true,
                        period: 4,
                        //constantSpeed: 1,
                        trailLength: 0.7,//0.7
                        color: '#fff',
                        symbolSize: 1
                    },
                    lineStyle: {
                        normal: {
                            color: color[i],
                            width: 0,
                            curveness: 0.2
                        }
                    },
                    data: convertData(item[1])
                },
                { // 绿线箭头
                    name: item[0] + ' Beijing',
                    type: 'lines',
                    zlevel: 2,
                    //symbol: ['none', 'arrow'],
                    symbolSize: 10,
                    effect: {
                        show: true,
                        period: 4,
                        trailLength: 0,
                        symbol: 'arrow', //planePath,
                        symbolSize: [2, 8], //8
                        color: '#ff0'
                    },
                    tooltip : {
                        trigger: 'item',
                        enterable: true,
                        formatter: function (params, ticket, callback) {
                            console.log(params, ticket);
                            return '源IP：' + params.data.ip_src + '<br>目标IP：' + params.data.ip_dst + '<br>攻击次数：' + params.data.att_times;
                        }

                    },
                    //hoverAnimation: true,
                    lineStyle: {
                        normal: {
                            color: color[i],
                            width: 0.5,
                            //type: 'dotted',
                            opacity: 0.6,
                            curveness: 0.2
                        }
                    },
                    data: convertData(item[1])
                },
                { // 涟漪圈圈
                    name: item[0] + ' Beijing',
                    type: 'effectScatter',
                    coordinateSystem: 'geo',
                    zlevel: 2,
                    //legendHoverLink: true,
                    rippleEffect: {
                        brushType: 'stroke'
                    },
                    label: {
                        normal: {
                            show: true,
                            position: 'right',
                            formatter: '{b}'
                        }
                    },
                    symbolSize: function (val) {
                        return val[2] / 8;
                    },
                    itemStyle: {
                        normal: {
                            color: color[i]
                        }
                    },
                    data: item[1].map(function (dataItem) {
                        return {
                            name: dataItem[1].name,
                            value: geoCoordMap[dataItem[1].name].concat([dataItem[1].value])
                        };
                    })
                }
            );
        });


        option = {
            /*title: {
             text: 'zhaoyao',
             show: true,
             link: 'http://www.tyoupub.com/',
             target: 'self',
             /!*textStyle: {
             color: '#00f',
             fontStyle: 'oblique', //italic normal
             fontWeight: 'bold',
             fontFamily: '微软雅黑',
             fontSize: 20
             },*!/
             //textAlign: 'center'
             subtext: 'abcdefg - sub text',
             sublink: 'http://www.tyoupub.com/',
             right: '3%',
             top: '3%',
             backgroundColor: 'rgba(0,0,0,.1)',
             borderColor: '#690',
             borderWidth: '100',
             shadowBlur: {
             shadowColor: 'rgba(0, 0, 0, 0.3)',
             shadowBlur: 10
             },
             shadowColor: {
             show: true
             },
             shadowOffsetX: '10',
             shadowOffsetY: '10',

             },*/
            backgroundColor: '#404a59',
            tooltip: {
                trigger: 'item',
                enterable: true,
                hideDelay: 1000,
                formatter: '{b}'
            },
            legend: {
                orient: 'vertical',
                top: 'bottom',
                left: 'right',
                data: ['Beijing'],
                textStyle: {
                    color: '#fff'
                },
                selectedMode: 'single'
            },
            geo: [
                {
                    name: '世界地图',
                    type: 'map',
                    map: 'world',
                    roam: true,
                    center: [10.97, 19.71],//[115.97, 29.71],
                    //aspectScale: 0.75,
                    //layoutCenter: ['50%', '50%'],
                    //layoutSize: 1800,
                    itemStyle: {
                        normal: {
                            areaColor: '#323c48',
                            borderColor: '#7ecef4' //'#404a59'
                        },
                        emphasis: {
                            areaColor: '#2a333d'
                        }
                    },
                    zoom: 1.35,//
                    selectedMode : 'single', //multiple
                    label:{
                        normal: {
                            show:false,
                            formatter: function (params) {
                                return nameMap[params.name];
                            }
                        },
                        emphasis: {
                            label:{
                                show:true,
                            }
                        }
                    },
                    regions: [{
                        name: 'China',
                        //selected: true,
                        itemStyle: {
                            normal: {
                                areaColor: '2a333d',
                            }
                        }
                    }]
                }
            ],
            series: series
        };

        myChart.setOption(option);
    });
</script>
@endpush