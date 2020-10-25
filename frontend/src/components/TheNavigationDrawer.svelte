<script>
  import { NavigationDrawer, List, ListItem, Icon } from 'svelte-materialify/src';
  import {mdiCalendar, mdiAccountCowboyHat, mdiCalendarPlus} from '@mdi/js';
  import format from "date-fns/format";
  import {de} from "date-fns/locale";
  import {createEventDispatcher} from 'svelte'

  const dispatch = createEventDispatcher();

  // format a unix timestamp to date/time string
  const formatDateTime = timestamp => {
    return format(new Date(timestamp), 'd.M. HH:mm', {locale: de}) + " Uhr";
  };

  export let admin = false;
  export let show = false;
  export let events = [];
</script>

<NavigationDrawer fixed active={show}>
    <List>
        <ListItem>
            <span style="font-weight: bold">Termine</span>
        </ListItem>
        {#each events as event}
            <ListItem on:click={() => dispatch("eventclick", {eventId: event.event_id})}>
            <span slot="prepend">
                <Icon path={mdiCalendar}/>
            </span>
                {formatDateTime(event.time)}
            </ListItem>
        {/each}
    </List>
    <span slot="append">
        <List>
            {#if admin}
                <ListItem on:click={() => dispatch("new")}>
                    <span slot="prepend">
                        <Icon path={mdiCalendarPlus}/>
                    </span>
                    Neuer Termin
                </ListItem>
            {/if}
            <ListItem on:click={() => dispatch("adminclick")}>
                <span slot="prepend">
                    <Icon path={mdiAccountCowboyHat}/>
                </span>
                    Admin {admin ? "Abmelden" : "Anmeldung"}
            </ListItem>
            <ListItem on:click={() => window.location.href = "http://kjg-stjoseph-speyer.de/datenschutzerklaerung.html"}>
                    Datenschutzerklärung
            </ListItem>
            <ListItem on:click={() => window.location.href = "http://kjg-stjoseph-speyer.de/impressum.html"}>
                    Impressum
            </ListItem>
        </List>
    </span>
</NavigationDrawer>

<style>
    .NavigationDrawer {
        width: 50%;
    }
</style>

