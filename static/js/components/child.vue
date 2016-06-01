<template>
    <div>{{parentMsg}}</div>
    <code>{{msg}}</code>
</template>
<script>
    export default{
        data(){
            return {
                msg: 'hello vue'
            }
        },
        props: {
            parentMsg: String
        }
    }
</script>