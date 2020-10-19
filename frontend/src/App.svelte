<script>
  import {MaterialApp} from 'svelte-materialify';
  import {Alert} from 'svelte-materialify/src';
  import TheAppBar from "./components/TheAppBar.svelte";
  import TheFooter from "./components/TheFooter.svelte";
  import EventAccordion from "./components/EventAccordion.svelte";

  import {testEvents} from './dummyData';
  let events = testEvents;

  const theme = "light";

  // called when user requests registration on an event
  const handleRegistration = info => {
    console.log(info);

    // TODO: register using API
    // for now just add the changes locally

    // get event by id
    const eventToUpdateIndex = events.findIndex(e => e.id === info.eventId);
    const eventToUpdate = events[eventToUpdateIndex];
    const isWaiting = eventToUpdate.participants.length >= eventToUpdate.maxParticipants;

    // check for duplicate name
    if (!eventToUpdate.participants.find(p => p.name === info.name)){
      eventToUpdate.participants = [...eventToUpdate.participants, {name: info.name, timePreference: info.time.getTime(), waiting: isWaiting}]
      events.splice(eventToUpdateIndex, 1, eventToUpdate);

      // trigger reactivity
      events = events;
    }else {
      console.log("Duplicate")
    }

  };
</script>

<MaterialApp {theme}>
    <TheAppBar />
    <Alert class="info-alert blue white-text ma-2" border="left" dense>
        <h5>Hinweis</h5>
        Bitte Hygienehinweise beachten!
    </Alert>

    <h4 class="heading mt-8 ml-1 mb-1">Termine</h4>
    <EventAccordion
            on:register={e => handleRegistration(e.detail)}
            events={events}
    />
    <TheFooter />
</MaterialApp>


<style>
    .info-alert {
        margin: 15px;
    }
</style>
