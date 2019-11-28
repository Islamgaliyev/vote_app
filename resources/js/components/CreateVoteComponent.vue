<template>
    <div class="row">
         <poll-creator savePollUrl="/my/vote/store" />
    </div>
</template>

<script>
    import {PollView, PollCreator} from '@updivision/vue-easy-polls'

    export default {     
        data() {
            return {
             
            }
        },
        components: {
            PollCreator,
            PollView
        }
        
    }
</script>