<script>
  import {Chip, Icon} from 'svelte-materialify/src';
  import {mdiAccountCheck, mdiAccountClock} from '@mdi/js';
  import format from "date-fns/format";
  import {de} from "date-fns/locale";

  const formatTime = timestamp => {
    return format(new Date(timestamp), 'HH:mm', {locale: de}) + " Uhr";
  }

  export let participant;
</script>

<Chip class="ma-1">
    <Icon path={participant.waiting ? mdiAccountClock : mdiAccountCheck} />
    <div class="d-flex flex-column">
        <span class="mt-2">{participant.name}</span>
        {#if participant.timePreference > 0}
            <span class="time mb-2" style="margin-top: -5px">Ab {formatTime(participant.timePreference)}</span>
        {/if}
    </div>
</Chip>

<style>
    .time {
        font-size: 8pt;
        font-style: italic;
    }
</style>
