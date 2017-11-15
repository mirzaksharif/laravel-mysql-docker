<!DOCTYPE html>
<html>
    <head>
        <title>API Call</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: bold;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 46px;
            }
        </style>

        <script src="https://unpkg.com/vue"></script>
        <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
        <script src="https://unpkg.com/vue-chartjs/dist/vue-chartjs.full.min.js"></script>

    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">VueJS & ChartJS API Call</div>
            </div>
        </div>

        <div id="app">
            <p align="center"><strong>Status:</strong> @{{ status }}</p>
            <line-chart></line-chart>
        </div>
        
        <script>
            
            var vm = new Vue ({             
                el: '#app',
                data: { 
                    status: '',
                    graphPoints:[],
                    graphLegends:[],
                    object: {}
                },
                //Runs onLoad
                created: function () {
                    this.loadData();
                },
                methods: { 
                    loadData: function () {
                        this.status = 'Loading API data...';
                        
                        var vm = this;
                        
                        axios.get('apicall')
                        .then ( function (response) {
                            vm.status = 'Loaded';
                            vm.object = response.data;
                            
                            for (i=0; i <vm.object.length ; i++) {
                                //Populate graph arrays...
                                vm.graphPoints.push(vm.object[i].cost);
                                vm.graphLegends.push(vm.object[i].dated);
                            }
                            console.log(vm.graphPoints);
                            console.log(vm.graphLegends);
                                    
                            //Chart.JS Library
                            Vue.component('line-chart', {
                              extends: VueChartJs.Line,
                              mounted () {
                                this.renderChart({
                                  labels: vm.graphLegends,
                                  datasets: [
                                    {
                                      label: 'Amounts are in $',
                                      backgroundColor: '#f87979',
                                      data: vm.graphPoints
                                    }
                                  ]
                                }, {responsive: true, maintainAspectRatio: false})
                              }
                            })
                            
                            
                        }) 
                        .catch (function (error){
                            vm.status = 'Invalid API';
                            console.log(error);
                        })
                    }
                }
            })
        </script>

    </body>
</html>
