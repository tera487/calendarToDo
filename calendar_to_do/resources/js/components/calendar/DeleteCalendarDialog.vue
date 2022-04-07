<template>
    <v-dialog value="true" persistent max-width="290">
        <v-card>
            <v-card-title class="headline">{{
                selectedEventName
            }}</v-card-title>
            <v-card-text>削除してもよろしいですか？</v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" text @click="$emit('colseDialog')"
                    >キャンセル</v-btn
                >
                <v-btn
                    color="green darken-1"
                    text
                    @click="deleteEvent(selectedEventId)"
                    >削除</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>
<script>
export default {
    props: {
        selectedEventId: { type: Number },
        selectedEventName: { type: String },
    },
    methods: {
        deleteEvent(id) {
            axios
                .delete(`/api/calendar/${id}`)
                .then(() => {
                    this.$emit('colseDialog', id);
                })
                .catch((err) => console.log(err))
                .finally(() => (this.loading = false));
        },
    },
};
</script>
