<script setup>
import FullCalendar from '@fullcalendar/vue3'
import { useForm } from '@inertiajs/vue3';
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import { ref, onBeforeMount, computed } from 'vue'
import interactionPlugin from '@fullcalendar/interaction'
import dayjs from "dayjs";
import { START_TIME_TYPE, START_TIME_TYPE_TEXT } from '@/Consts/startTimeType.js';

const props = defineProps({
    reservations: {
        type: Array,
        required: true,
    },
    isAdmin: {
        type: Boolean,
        required: true,
    },
});
const user = computed(() => page.props.auth.user)
const form = useForm({
    event: {
        name: '',
    }
});
onBeforeMount(() => {
    console.log(props.reservations)
    props.reservations.map((reservation) => {
        form.event.name = reservation.user.name
        let startTime = dayjs(`${reservation.date}: ${START_TIME_TYPE_TEXT[reservation.start_time_type]}`).format('YYYY-MM-DDTHH:mm:ss')
        startTime = startTime+'Z'
        // calendarOptions.events.push({
        //     title: 'title',
        //     date: '2023-11-26',
        //     start: '2023-11-26T13:00:00Z',
        //     // startTime: startTime,
        //     // endTime: reservation.end_time,
        //     allDay: false
        // })
        events.push({
            id: reservation.id,
            title: reservation.user.name+'様',
            date: reservation.date,
            // start: startTime,
            start: startTime,
            // endTime: reservation.end_time,
            allDay: false
        })
    })
    console.log(events)
})
// const events = ref([])
const events = [
    {
        title: '休憩時間',
        color: '#FF0000',
        // start:  '2023-11-28',
        // end: '2023-11-29',
        date: '2023-11-28',
        startTime: '11:00',
        endTime: '12:00',
        allDay: false
    },
]
const handleDateSelect = (selectInfo) => {
    // alert('handleDate')
    events.push({ title: 'Taro', date: '2023-11-38' })
}
const handleEventClick = (clickInfo) => {
    console.log(clickInfo)
    let reservationId = clickInfo.event.id
    let menus = []
    props.reservations.map((reservation) => {
        if (reservation.id == reservationId) {
            console.log(reservation)
            reservation.payment_histories.map((paymentHistory) => {
                menus.push(paymentHistory.menu)
            })
        }
    })
    isOpen.value = true;
    console.log(dayjs(clickInfo.event.startStr).add('hours', 1))
    form.event = {
        name: clickInfo.event.title,
        date: dayjs(clickInfo.event.startStr).format('YYYY-MM-DD'),
        startTime: dayjs(clickInfo.event.startStr).format('YYYY-MM-DD:HH:mm:ss'),
        endTime: dayjs(clickInfo.event.startStr).add(1, 'hours').format('YYYY-MM-DD:HH:mm'),
        menus: menus,
    }
}
const handleEvents = (events) => {
    alert('handleEvents')
}
const calendarOptions = {
    locale: 'ja',
    timeZone: 'UTC',
    plugins: [ dayGridPlugin, interactionPlugin, timeGridPlugin ],
    headerToolbar: {
        left: 'prev,next',
        center: 'title',
        right: ''
    },
    droppable: false,
    editable: false,
    selectable: true,
    initialView: 'timeGridWeek',
    select: function(selectInfo) {
        if (props.isAdmin) {
            return
        }
        // let title = prompt('Please enter a new title for your event')
        let calendarApi = selectInfo.view.calendar
        console.log(selectInfo)

        calendarApi.unselect()

        console.log(dayjs(selectInfo.startStr).format('YYYY-MM-DD:HH:mm'))
        const startStr = dayjs(selectInfo.startStr).format('HH:mm')
        const endStr = dayjs(selectInfo.startStr).add(1, 'hours').format('HH:mm')
        if (selectInfo.startStr.substring(14, 16) === '30') {
            return
        }
        form.event.value = {
            title: form.name,
            date: dayjs(selectInfo.startStr).format('YYYY-MM-DD:HH:mm'),
            startTime: startStr,
            endTime: endStr,
            allDay: selectInfo.allDay
        }
        // calendarApi.addEvent({
        //     title: form.name,
        //     start: selectInfo.startStr,
        //     allDay: selectInfo.allDay
        // })
    },
    eventClick: handleEventClick,
    // eventsSet: handleEvents,
    events: events,
    // dateClick: function(info) {
    //     alert('clicked ' + info.dateStr)
    //     console.log(info)
    //     events.push({ title: 'Taro', date: info.dateStr })
    // },
    // eventClick: function(info) {
    //     console.log(info)
    //     alert('clicked ' + info.dateStr)
    // },
    allDaySlot: false,
    eventShortHeight: 60,
    slotMinTime: '10:00:00',
    slotMaxTime: '23:00:00',
    
    // initialView: 'timeGridLimitedDay',
    // views: {
    //     timeGridLimitedDay: {
    //         type: 'timeGrid',
    //         duration: { days: 4 },
    //         eventShortHeight: '02:00'
    //     }
    // }
}
const isOpen = ref(false);
const emits = defineEmits([
    'delete:modelValue',
]);
const close = () => {
    isOpen.value = false;
    emits('delete:modelValue')
}
</script>
<template>
    <pre>{{ form.event }}</pre>
    <FullCalendar :options="calendarOptions" />
    <v-dialog
        v-model="isOpen"
        width="500px"
        >
        <v-card class="px-8 pt-4">
            <v-card-text class="mb-4 text-center">
            予約詳細
            </v-card-text>
            <v-row>
                <v-col cols="4">
                    <v-list-subheader>お名前</v-list-subheader>
                </v-col>
                <v-col cols="8">
                    <v-list-item>
                        <v-list-item-title>{{ form.event.name }}</v-list-item-title>
                    </v-list-item>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    <v-list-subheader>開始時刻</v-list-subheader>
                </v-col>
                <v-col cols="8">
                    <v-list-item>
                        <v-list-item-title>{{ form.event.startTime }}</v-list-item-title>
                    </v-list-item>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    <v-list-subheader>終了時刻</v-list-subheader>
                </v-col>
                <v-col cols="8">
                    <v-list-item>
                        <v-list-item-title>{{ form.event.endTime }}</v-list-item-title>
                    </v-list-item>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    <v-list-subheader>注文内容</v-list-subheader>
                </v-col>
                <v-col cols="8">
                    <v-row v-for="(menu, index) in form.event.menus">
                        <v-col cols="4">
                            <v-list-subheader>{{ menu.name }}</v-list-subheader>
                        </v-col>
                        <v-col cols="8">
                            <v-list-item>
                                <v-list-item-title>{{ menu.price}}</v-list-item-title>
                            </v-list-item>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12">
                    <v-btn class="text-subtitle-1" variant="flat" block @click="isOpen = false">閉じる</v-btn>
                </v-col>
            </v-row>
        </v-card>
    </v-dialog>
</template>