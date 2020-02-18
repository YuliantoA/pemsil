var app = new Vue({
    el: '#app',
    data: {
        data : [],
        len: 10,
        lenTable:0,
        qty : [],
        demand : [],
        prob : [],
        komu : [],
        intv : [],
        gNumber : 0,
        dTot : 0,
        res : [],
        minta : [],
        week : [],
        minggu : [],
        frekuensi : [],
        datTable:[],
        temp:[],
        counterA:0,
        mingguIn:0,
        freqIn:0,
        isLoading:false
    },
    
    mounted () {
        this.getData()
    },
    methods:{
        getData : function(){
            axios
            .get('http://localhost/pemsil_montecarlo/api/pemsil/get',{ 
                    crossdomain: true,
                    cache : false 
            })
            .then(function (response) {
                console.log(response)
                app.data = response.data.data
                app.len = response.data.data.length
                app.checking()
            })
            .catch(error => console.log(error))
        },
        checking : function(){
            this.datTable = []
            for(i=0;i<=Math.max.apply(Math,this.data.map(o => o.frekuensi));i++){
                if (this.data.filter(x => x.frekuensi == i).length > 0){
                    this.datTable.push({
                        freq : this.data.filter(x => x.frekuensi == i).length,
                        minta : i
                    })
                }
            }
            this.getProb()
            this.getKomu()  
            this.getIntv()
        },
        generateChart : function() {
            var cat = []
            var dat = []
            for(i = 0;i < this.res.length;i++){
                cat[i] = this.res[i].week
                dat[i] = parseInt(this.res[i].freqIdx)
            }
            console.log(dat)
            Highcharts.chart('container', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Prediksi Penjualan Laptop'
                },
                subtitle: {
                    text: 'Monte Carlo'
                },
                xAxis: {
                    categories: cat 
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Penjualan'
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: true
                    }
                },
                series: [{
                    name: 'Data',
                    data: dat
                }]
            })
        },
        getKomu : function(){
            for(i = 0;i <= this.datTable.length-1;i++){
                if(i === 0){
                    this.komu[i] = parseFloat(this.prob[i]).toFixed(3)
                }
                else if(i == this.datTable.length-1){
                    this.komu[i] = Math.round(parseFloat(this.prob[i])+parseFloat(this.komu[i-1]))
                }
                else{
                    this.komu[i] = (parseFloat(this.prob[i])+parseFloat(this.komu[i-1])).toFixed(3)
                }
                // console.log(this.komu[i])
            }
        },
        getProb : function(){
            this.dTot = this.datTable.reduce((acc, item) => acc + item.freq, 0);
            for(i = 0;i <= this.datTable.length-1;i++){
                this.prob[i] = (parseInt(this.datTable[i].freq)/parseInt(this.dTot)).toFixed(3)
            }
        },
        getIntv : function(){
            for(i = 0;i <= this.datTable.length-1;i++){
                if(i === 0){
                    this.intv[i] = "0 - "+ this.komu[i]
                }
                else{
                    this.intv[i] = this.datTable[i].minta == '0' ? '-' : (parseFloat(this.komu[i-1])+(0.001))+" - "+this.komu[i]
                }
            }
        },
        generate : function(){
            this.gNumber = parseFloat(Math.random()).toFixed(3)
            this.classify()
            this.generateChart()
        },
        input : async function(){
            this.isLoading = true 
            const params = new URLSearchParams();
            params.append('minggu', this.mingguIn);
            params.append('frekuensi', this.freqIn);
            await axios
            .post('http://localhost/pemsil_montecarlo/api/pemsil/',params
            ,{
                headers: { 
                    'Content-type': 'application/x-www-form-urlencoded',
                }
            })
            .then(async function (response){
                await app.getData()
                app.isLoading = false
            })
            .catch(error => console.log(error))
            
        },
        updateData(id){
            console.log(id.id)
            console.log(id.frekuensi)
            console.log(id.minggu)
            const params = new URLSearchParams();
            params.append('id', id.id);
            params.append('minggu', id.minggu);
            params.append('frekuensi',id.freq);
            axios
            .put('http://localhost/pemsil_montecarlo/api/pemsil',params,{
                headers: { 
                    'Content-type': 'application/x-www-form-urlencoded',
                },
            })
            .then(response => (console.log(response)))
            .catch(error => console.log(error))
            this.checking();
        },
        classify : function(){
            if(this.res.length > 0){
                this.getProb()
                this.getKomu()
                this.getIntv()
            }
            for(i = 0;i <= this.datTable.length-1;i++){
                if(parseFloat(this.gNumber) <= parseFloat(this.komu[i])){
                    this.datTable[i].freq = parseInt(this.datTable[i].freq)+parseInt(1)
                    
                    this.res.push(
                        {
                            week : parseInt(this.dTot)+1,
                            gNum : parseFloat(this.gNumber),
                            freqIdx : this.datTable[i].minta
                        }
                    )
                    // console.log(i)
                    break;
                }
            }
        }
    },
    watch : {
        len : function(){
            for(i = 1;i <= this.len;i++){
                
                this.qty[i] = 0
                this.demand[i] = 0
                this.prob[i] = 0
                this.komu[i] = parseFloat(0)
                this.intv[i] = ''
            }
            this.checking()
        }
    },
    created : function(){
        for(i = 1;i <= this.len;i++){
            this.qty[i] = i
            this.demand[i] = i
            this.prob[i] = 0
            this.komu[i] = parseFloat(0)
            this.intv[i] = ''
        }
    }
})