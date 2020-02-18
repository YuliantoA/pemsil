<html>
    <head>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <style>
.highcharts-figure, .highcharts-data-table table {
    min-width: 360px; 
    max-width: 800px;
    margin: 1em auto;
}

.highcharts-data-table table {
	font-family: Verdana, sans-serif;
	border-collapse: collapse;
	border: 1px solid #EBEBEB;
	margin: 10px auto;
	text-align: center;
	width: 100%;
	max-width: 500px;
}
.highcharts-data-table caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}
.highcharts-data-table th {
	font-weight: 600;
    padding: 0.5em;
}
.highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
    padding: 0.5em;
}
.highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
    background: #f8f8f8;
}
.highcharts-data-table tr:hover {
    background: #f1f7ff;
}
        </style>
    </head>
    <body>
        <div id="app"> 
            <div class="container">
                <div style="height: 400px;overflow-y: scroll;margin-bottom:50px;margin-top: 50px;">
                    <table class="table table-bordered text-center" v-if="!isLoading">
                        <thead>
                            <th>Minggu Ke</th>
                            <th>Permintaan</th>
                        </thead>
                        <tbody>
                            <tr v-for="id in data" >
                                <td>{{ id.minggu }}</td>
                                <td><input style="width: 100px;margin:auto;" type="text" class="form-control" v-model="id.frekuensi" @change="updateData(id)"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="margin-bottom:50px;margin-top: 50px;">
                    <label for="exampleInputEmail1">Data Input Faktual</label>
                    <table class="table table-bordered text-center">
                        <thead>
                            <th>Minggu Ke</th>
                            <th>Permintaan</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input style="width: 100px;margin:auto;" type="text" class="form-control" v-model="mingguIn"></td>
                                <td><input style="width: 100px;margin:auto;" type="text" class="form-control" v-model="freqIn"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="input-group-prepend">
                        <button class="btn btn-outline-primary" type="button" id="button-addon1" @click="input">Input Data</button>
                    </div>
                </div>
                <!-- <div class="form-group">
                    <label for="exampleInputEmail1">Data Input Faktual</label>
                    <input type="text" v-model="len" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                    <input type="text" v-model="len" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div> -->
                <table class="table table-bordered text-center">
                    <thead>
                        <th>Permintaan</th>
                        <th>Frekuensi</th>
                        <th>Prob</th>
                        <th>Komulatif</th>
                        <th>Interval</th>
                    </thead>
                    <tbody>
                        <tr v-for="(n,index) in datTable"> 
                            <td>{{n.minta}}</td>
                            <td>{{n.freq}}</td>
                            <td>{{ prob[index] }}</td>
                            <td>{{ komu[index] }}</td>
                            <td>{{ intv[index] }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah</td>
                            <td>{{ dTot }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <button class="btn btn-outline-primary" type="button" id="button-addon1" @click="generate">Generate Random Number</button>
                    </div>
                    <input type="text" class="form-control" v-model="gNumber" readonly placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                  </div>
                <div style="height: 400px;overflow-y: scroll;">
                    <table class="table table-bordered text-center">
                        <thead>
                            <th>Minggu Ke</th>
                            <th>Random Number</th>
                            <th>Permintaan</th>
                        </thead>
                        <tbody>
                            <tr v-for="(i,idx) in res">
                                <td>{{ i.week }}</td>
                                <td>{{ i.gNum }}</td>
                                <td>{{ i.freqIdx }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>  
                <figure class="highcharts-figure">
                    <div id="container"></div>
                </figure>
            </div>
        </div>
    </body>
    <script src="vue.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="my.js"></script>
    <script>
    </script>
</html>