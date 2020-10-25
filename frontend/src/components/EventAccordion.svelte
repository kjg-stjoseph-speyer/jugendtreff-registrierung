<script>
  import {createEventDispatcher, afterUpdate} from 'svelte'
  import {readCookie} from "../CookieHelper";
  import ExpansionPanels, {ExpansionPanel} from 'svelte-materialify/src/components/ExpansionPanels';
  import {Button, Icon} from 'svelte-materialify/src';
  import {mdiAccountArrowRight, mdiAccountGroup, mdiLogout} from '@mdi/js';
  import ParticipantChip from "./ParticipantChip.svelte";
  import format from "date-fns/format";
  import {de} from "date-fns/locale";
  import RegisterDialog from "./RegisterDialog.svelte";
  import ConfirmationDialog from "./ConfirmationDialog.svelte";

  const dispatch = createEventDispatcher();

  // returns number of waiting participants in list
  const countWaiting = participants => {
    return participants.filter(p => p.waiting).length;
  };

  // format a unix timestamp to date/time string
  const formatDateTime = timestamp => {
    return format(new Date(timestamp), 'EEEE d. MMM. HH:mm', {locale: de}) + " Uhr";
  };

  let registerDialogVisible = false;
  let registerEventName = "";
  let registerEventTime = new Date();

  let currentEventId = -1;
  let currentUserId = -1;
  let showConfirmEventDelete = false;
  let showConfirmUserDelete = false;
  let showConfirmUserToggle = false;

  const handleRegisterClick = (event) => {
    if (!isUserRegistered(event.registrations, userinfo.name)) {
      // register user
      currentEventId = event.event_id;
      registerEventName = formatDateTime(event.time);
      registerEventTime = new Date(event.time);
      registerDialogVisible = true;
    } else {
      // remove registration
      deregisterUser(event)
    }
  };
  const registerUser = (event) => {
    dispatch("register", {
      ...event.detail,
      eventId: currentEventId
    })

    registerDialogVisible = false;
  };
  const deregisterUser = event => {
    dispatch("deregister", {
      eventId: event.event_id,
      userId: userinfo.userid
    })
  }

  const isUserRegistered = (participants) => {
    return participants.find(p => p.user_id === userinfo.userid) !== undefined;
  }

  let userinfo = {name: "", email: ""};
  afterUpdate(async () => {
    const cookie = readCookie("userinfo");
    if (cookie !== "") {
      // userinfo cookie exists
      userinfo = JSON.parse(cookie);
    }
  });

  let accordionShow = [0];

  $: {
    accordionShow = [expandedIndex];
  }

  export let admin = false;
  export let events;
  export let expandedIndex = 0;
</script>


<RegisterDialog
        defaultName={userinfo.name}
        defaultEmail={userinfo.email}
        eventName={registerEventName}
        eventTime={registerEventTime}
        active={registerDialogVisible}
        on:cancel={() => registerDialogVisible = false}
        on:save={registerUser}
/>
<ExpansionPanels accordion bind:value={accordionShow}>
    {#each events as event (event.event_id)}
        <ExpansionPanel>
            <div slot="header" class="d-flex flex-column">
                <span class="event-header">{formatDateTime(event.time)}</span>

                <div class="d-flex align-center mt-2">
                    <Icon size="16px" path={mdiAccountGroup}/>
                    <span class="ml-2">{event.registrations.length} / {event.max_participants}</span>
                </div>
            </div>
            <div class="d-flex flex-column" style="width: 100%">
                {#if admin}
                    <div style="width: 100%">
                        <Button
                                style="width: 48%"
                                class="float-left mt-1 mb-2 yellow darken-2"
                                size="small"
                                on:click={() => dispatch("adminedit", {eventId: event.event_id})}
                        >
                            Bearbeiten
                        </Button>
                        <Button
                                style="width: 48%"
                                class="float-right mt-1 mb-2 red lighten-1"
                                size="small"
                                on:click={() => {currentEventId = event.event_id; showConfirmEventDelete = true}}
                        >
                            Löschen
                        </Button>
                    </div>
                {/if}

                <div style="width: 100%">
                    <h6 class="heading float-left">Teilnehmer</h6>
                    {#if !admin}
                        <Button
                                size="small"
                                class={
                                    (isUserRegistered(event.registrations) ?
                                    "mt-1 float-right red lighten-1" :
                                    "mt-1 float-right light-green lighten-2")
                                }
                                on:click={() => handleRegisterClick(event)}
                        >
                            <Icon size="20px" path={isUserRegistered(event.registrations) ? mdiLogout : mdiAccountArrowRight} class="mr-1"/>
                            {isUserRegistered(event.registrations) ? "Abmelden" : "Registrieren"}
                        </Button>
                    {/if}
                </div>
                <div class="mt-3">
                    {#if event.registrations.length > 0}
                        <!-- list all participants -->
                        {#each event.registrations as p (p.name)}
                            {#if !p.waiting}
                                <ParticipantChip
                                        on:close={() => {currentUserId = p.user_id; currentEventId = event.event_id; showConfirmUserDelete = true}}
                                        on:click={() => {currentUserId = p.user_id; currentEventId = event.event_id; showConfirmUserToggle = true}}
                                        allowClose={admin}
                                        participant={p}
                                />
                            {/if}
                        {/each}
                    {:else}
                        <span class="italic-text">Keine Teilnehmer</span>
                    {/if}

                </div>

                {#if countWaiting(event.registrations) > 0}
                    <h6 class="heading float-left mt-3">Warteliste</h6>
                    <div class="mt-3">
                        {#each event.registrations as p (p.name)}
                            {#if p.waiting}
                                <ParticipantChip
                                        on:close={() => {currentUserId = p.user_id; currentEventId = event.event_id; showConfirmUserDelete = true}}
                                        on:click={() => {currentUserId = p.user_id; currentEventId = event.event_id; showConfirmUserToggle = true}}
                                        allowClose={admin}
                                        participant={p}
                                />
                            {/if}
                        {/each}
                    </div>
                {/if}
                <span class="italic-text mt-6">Verantwortlicher: {event.in_charge}</span>
            </div>
        </ExpansionPanel>
    {/each}
</ExpansionPanels>
{#if admin}
    <ConfirmationDialog
        show={showConfirmEventDelete}
        title="Termin löschen"
        text="Jugentreff Termin wirklich löschen? Angemeldete Teilnehmer werden über die Löschung benachrichtigt"
        on:cancel={() => showConfirmEventDelete = false}
        on:confirm={() => {dispatch("admindelete", {eventId: currentEventId}); showConfirmEventDelete = false}}
    />
    <ConfirmationDialog
            show={showConfirmUserDelete}
            title="Teilnehmer entfernen"
            text="Teilnehmer wirklich entfernen? Der Teilnehmer und eventuelle Nachrücker werden benachrichtigt"
            on:cancel={() => showConfirmUserDelete = false}
            on:confirm={() => {dispatch("adminderegister", {eventId: currentEventId, userId: currentUserId}); showConfirmUserDelete = false}}
    />
    <ConfirmationDialog
            show={showConfirmUserToggle}
            title="Teilnehmer verschieben"
            text="Teilnehmer wirklich verschieben? Der Teilnehmer wird benachrichtigt"
            on:cancel={() => showConfirmUserToggle = false}
            on:confirm={() => {dispatch("admintoggle", {eventId: currentEventId, userId: currentUserId}); showConfirmUserToggle = false}}
    />
{/if}

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
