<script>
  import {Chip, Icon} from 'svelte-materialify/src';
  import {mdiAccountCheck, mdiAccountClock, mdiCloseCircle} from '@mdi/js';
  import format from "date-fns/format";
  import {de} from "date-fns/locale";
  import {createEventDispatcher} from "svelte";

  const dispatch = createEventDispatcher();

  const formatTime = timestamp => {
    return format(new Date(timestamp), 'HH:mm', {locale: de}) + " Uhr";
  }

  export let participant;
  export let allowClose = false;
</script>

<Chip class="ma-1" >
    <Icon path={participant.waiting ? mdiAccountClock : mdiAccountCheck} />
    <div on:click={() => dispatch("click")} class="d-flex flex-column">
        <span class="mt-2">{participant.name}</span>
        {#if participant.time > 0}
            <span class="time mb-2" style="margin-top: -5px">Ab {formatTime(participant.time)}</span>
        {/if}
    </div>
    {#if allowClose}
        <a class="ml-3" on:click={() => dispatch("close")}>
            <Icon size="20px" path={mdiCloseCircle} />
        </a>
    {/if}
</Chip>

<style>
    .time {
        font-size: 8pt;
        font-style: italic;
    }
</style>
