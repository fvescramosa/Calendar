<template>
    <div>
        <div class="events card card-body">
            <div class="events-container">
                <div class="events-container__title">
                    <h2>Calendar</h2>
                </div>
                <div class="events-container__form">
                    <div class="form-group">
                        <label class="col-md-4">Event Title</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control" v-model="eventTitle">
                            <span v-if="err !== 0" class="invalid-feedback show"  role="alert">
                                <strong v-for="title in err.eventTitle">{{ title }}</strong>
                            </span>
                        </div>
                    </div>
                    <div class="form-group row m-0">
                        <div class="col-md-6 ">
                            <label class="">From</label>
                            <div class="p-0 col-md-12">
                                <input type="date" class="form-control" min="10-01-2020" max="10-31-2020" v-model="fromDate">


                                <span v-if="err !== 0" class="invalid-feedback show"  role="alert">
                                 <strong v-for="from_date in err.fromDate">{{ from_date }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <label class="col-md-4 p-0">To</label>
                            <div class="p-0 col-md-12">
                            <input type="date" class="form-control" min="10-01-2020" max="10-31-2020" v-model="toDate">
                            <span v-if="err !== 0" class="invalid-feedback show"  role="alert">
                                <strong v-for="to_date in err.toDate">{{ to_date }}</strong>
                            </span>
                        </div>
                        </div>
                    </div>

                    <div class="col-12 my-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="" v-model="eventDay" value="Monday">
                            <label for="" class="form-check-label">Mon</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="" v-model="eventDay" value="Tuesday">
                            <label for="" class="form-check-label">Tue</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="" v-model="eventDay" value="Wednesday">
                            <label for="" class="form-check-label">Wed</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="" v-model="eventDay" value="Thursday">
                            <label for="" class="form-check-label">Thu</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="" v-model="eventDay" value="Friday">
                            <label for="" class="form-check-label">Fri</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="" v-model="eventDay" value="Saturday">
                            <label for="" class="form-check-label">Sat</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="" v-model="eventDay" value="Sunday">
                            <label for="" class="form-check-label">Sun</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <span v-if="err !== 0" class="invalid-feedback show"  role="alert">
                                <strong v-for="event_date in err.eventDay">{{ event_date }}</strong>
                            </span>
                        </div>
                    </div>

                    <div class="col-10">
                        <div class="form-group row justify-content-center">
                            <button class="btn btn-success" @click="setEvents()">Submit</button>
                        </div>
                    </div>
                </div>
                <div class="events-container__content">
                    <h1> {{ formattedDate.month  }}</h1>
                    <div  v-for="item in formattedDate.result" :class="'day'">
                        <div class="day-content">
                             <div class="day-content__day">
                                {{ item.format('DD') }} {{ item.format('ddd') }}
                             </div>
                            <div class="day-content__event" v-if="item.format('DD') >= from && item.format('DD') <= to">
                                <span v-for="day in event_days">
                                    {{(day.day == item.format('dddd')) ? (title != '') ? title : 'No Title' : ''}}
                                 </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import moment from 'moment';
    export default {
        mounted() {
            console.log('Component mounted.');
            this.fetchData();
        },
        data: function() {
            return {
                from : '',
                to : '',
                event_days : '',
                title : '',
                eventDay: [],
                eventTitle: '',
                fromDate: '',
                toDate: '',
                err: [],
                toastCount: 0,
                saveMessage: '',
                content: ''
            }
        },
        computed: {
            formattedDate() {

                    var month = 10;
                    var year = 2020;
                    var data = [];

                    var monthIndex = month - 1; // 0..11 instead of 1..12
                    var start = moment(year + '-' + month + '-' + '01');
                    var end = moment(year + '-' + month + '-' + '01').endOf('month');
                    var result = [];

                    for(var i = 1; i <= end.format('D'); i++ ){
                        result.push( moment(year + '-'  + month + '-'  +i) );
                    }

                    data['result'] = result;
                    data['month'] = start.format('MMMM');

                return data;
            },
        },
        methods: {
            moment (date) {
                return moment(date);
            },
            fetchData (){
                axios.post('/getevents', {month: 10})
                    .then(response => {
                        console.log(response);
                        this.from = moment(response.data[0].from).format('DD');
                        this.to = moment(response.data[0].to).format('DD');
                        this.event_days = response.data[0].event_days;
                        this.title = response.data[0].title;
                    })
            },
            setEvents (){
                axios.post('/set-event', {eventTitle: this.eventTitle, eventDay: this.eventDay, toDate: this.toDate, fromDate : this.fromDate })
                    .then(response => {
                        console.log(response);
                        this.fetchData();
                        this.err = '';
                        this.saveMessage = 'Event Successfully Saved';
                        this.makeToast()
                    })
                    .catch(error => {
                        this.err = error.response.data.errors;
                    });
            },
            makeToast(append = false) {
                this.toastCount++
                this.$bvToast.toast(this.saveMessage, {
                    title: '',
                    autoHideDelay: 5000,
                    appendToast: append
                })
            }
        }
    }
</script>
