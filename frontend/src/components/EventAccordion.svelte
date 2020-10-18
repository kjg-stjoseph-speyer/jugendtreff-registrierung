<script>
  import ExpansionPanels, {ExpansionPanel} from 'svelte-materialify/src/components/ExpansionPanels';
  import {Button, Icon} from 'svelte-materialify/src';
  import {mdiAccountArrowRight, mdiAccountGroup} from '@mdi/js';
  import ParticipantChip from "./ParticipantChip.svelte";
  import format from "date-fns/format";
  import {de} from "date-fns/locale";

  // returns number of waiting participants in list
  const countWaiting = participants => {
    return participants.filter(p => p.waiting).length;
  }

  // format a unix timestamp to date/time string
  const formatDateTime = timestamp => {
    return format(new Date(timestamp), 'EEEE d. MMM. HH:mm', {locale: de}) + " Uhr";
  }

  export let events;
</script>


<ExpansionPanels accordion>
    {#each events as event (event.id)}
        <ExpansionPanel>
            <div slot="header" class="d-flex flex-column">
                <span class="event-header">{formatDateTime(event.time)}</span>

                <div class="d-flex align-center mt-2">
                    <Icon size="16px" path={mdiAccountGroup}/>
                    <span class="ml-2">{event.participants.length} / {event.maxParticipants}</span>
                </div>
            </div>
            <div class="d-flex flex-column" style="width: 100%">
                <div style="width: 100%">
                    <h6 class="heading float-left">Teilnehmer</h6>
                    <Button size="small" class=" mt-1 float-right light-green lighten-2">
                        <Icon size="20px" path={mdiAccountArrowRight} class="mr-1"/>
                        Registrieren
                    </Button>
                </div>
                <div class="mt-3">
                    {#if event.participants.length > 0}
                        <!-- list all participants -->
                        {#each event.participants as p (p.name)}
                            {#if !p.waiting}
                                <ParticipantChip participant={p} />
                            {/if}
                        {/each}
                    {:else}
                        <span class="italic-text">Keine Teilnehmer</span>
                    {/if}

                </div>

                {#if countWaiting(event.participants) > 0}
                    <h6 class="heading float-left mt-3">Warteliste</h6>
                    <div class="mt-3">
                        {#each event.participants as p (p.name)}
                            {#if p.waiting}
                                <ParticipantChip participant={p} />
                            {/if}
                        {/each}
                    </div>
                {/if}
                <span class="italic-text mt-6">Verantwortlicher: {event.inCharge}</span>
            </div>
        </ExpansionPanel>
    {/each}
</ExpansionPanels>

<style>
    .italic-text {
        font-style: italic;
    }
    .event-header {
        font-size: 16pt;
    }
    h6 {
        font-size: 18pt;
    }
</style>
