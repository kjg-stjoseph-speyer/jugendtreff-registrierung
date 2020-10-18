<script>
  import {MaterialApp} from 'svelte-materialify';
  import {Alert} from 'svelte-materialify/src';
  import TheAppBar from "./components/TheAppBar.svelte";
  import TheFooter from "./components/TheFooter.svelte";
  import EventAccordion from "./components/EventAccordion.svelte";

  import {testEvents} from './dummyData';
  let events = testEvents;

  const theme = "light";
</script>

<MaterialApp {theme}>
    <TheAppBar />
    <Alert class="info-alert blue white-text ma-2" border="left" dense>
        <h5>Hinweis</h5>
        Bitte Hygienehinweise beachten!
    </Alert>

    <h4 class="heading mt-8 ml-1 mb-1">Termine</h4>
    <EventAccordion events={events} />
    <TheFooter />
</MaterialApp>


<style>
    .info-alert {
        margin: 15px;
    }
</style>
