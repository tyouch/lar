<?php
/**
 * Created by PhpStorm.
 * User: zhaoyao
 * Date: 2017/5/17
 * Time: 10:31
 */
?>

<script src="<?= url('js/bootstrap-treeview.min.js') ?>"></script>
<script>
    function convertData(arrays) {
        //console.log(arrays);
        var treeData = [];
        $.each(arrays, function (i, ele1) {
            treeData[i] = {
                text    : ele1.name,
                href    : 'javascript:;',//'' + ele1.name,
                tags    : [ele1.city.length.toString()],
                nodes   : []
            };
            var nodes_city = [];
            $.each(ele1.city, function(j, ele2) {
                nodes_city[j] = {
                    text    : ele2.name,
                    href    : 'javascript:;',//'' + ele2.name,
                    tags    : [ele2.area.length.toString()],
                    nodes   : []
                };
                var nodes_area = [];
                $.each(ele2.area, function(k, ele3){
                    nodes_area[k] = {
                        text    : ele3,
                        href    : 'javascript:;',//'' + ele3,
                        tags    : ['0'],
                    }
                });
                nodes_city[j].nodes = nodes_area;
            });
            treeData[i].nodes = nodes_city;
        });

        return treeData;
    }


    $(function() {

        $.getJSON("<?= url('js/ChineseCities.json') ?>", function (ChineseCities) {

            /*var treeData = [
             {
             text: '河南省',
             href: '#',
             tags: ['4'],
             nodes: [
             {
             text: '郑州市',
             href: '#',
             tags: ['2'],
             nodes: [
             {
             text: '金水区',
             href: '#grandchild1',
             tags: ['0']
             },
             {
             text: '二七区',
             href: '#grandchild2',
             tags: ['0']
             }
             ]
             },
             {
             text: '新政市',
             href: '#',
             tags: ['0']
             }
             ]
             },
             {
             text: '陕西省',
             href: '#parent2',
             tags: ['0']
             },
             {
             text: '山西省',
             href: '#parent3',
             tags: ['0']
             }
             ];*/

            console.log(ChineseCities);
            var treeData = convertData(ChineseCities);
            console.log(treeData);

            $('#treeview5').treeview({
                levels: 1,
                color           : "#15ceff",
                backColor       : "#404a59",
                expandIcon      : 'glyphicon glyphicon-chevron-right',
                collapseIcon    : 'glyphicon glyphicon-chevron-down',
                //nodeIcon      : 'glyphicon glyphicon-bookmark',
                enableLinks     : true,
                data            : treeData
            });

        });

    });
</script>

