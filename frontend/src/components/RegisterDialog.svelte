<script>
  import { createEventDispatcher } from 'svelte'
  import {mdiClock} from "@mdi/js";
  import { Card, CardTitle, CardActions, CardText, Dialog, Button, TextField, Icon } from 'svelte-materialify/src';
  import Flatpickr from "svelte-flatpickr";

  const dispatch = createEventDispatcher();

  let name = "";
  let email = "";
  let time = null;

  export let active;
  export let eventName;
  export let eventTime;
  export let defaultName = "";
  export let defaultEmail = "";

  // field verification
  const nameRules = [(v) => !!v || 'Pflichtangabe!', (v) => v.length <= 25 || 'Max. 25 Buchstaben'];
  const emailRules = [
    (v) => {
      const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return pattern.test(v) || 'Ungültige E-Mail Adresse';
    },
  ];

  let options = {
    enableTime: true,
    noCalendar: true,
    time_24hr: true,
    dateFormat: "H:i",
    wrap: true,
    onChange: (selectedDates, dateStr, instance) => {
      time = selectedDates[0].getTime()
    }
  };

  $: {
    time = eventTime;
    name = defaultName;
    email = defaultEmail;
  }

 const handleSubmit = () => {
    if (name.length > 0) {
      dispatch("save", {name, time, email});
    }
  }
</script>

<svelte:head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/themes/material_green.css">
</svelte:head>


<Dialog persistent bind:active>
    <Card>
        <CardTitle>Registrieren für {eventName}</CardTitle>
        <CardText>
            Gibt deinen Namen und die gewünschte Uhrzeit ein

            <TextField
                    hint="Vorname, Spitzname, o.Ä."
                    class="mt-3"
                    bind:value={name}
                    placeholder=""
                    counter="25"
                    validateOnBlur
                    tabIndex="-1"
                    rules={nameRules}
            >
                Name <sup>*</sup>
            </TextField>
            <Flatpickr
                    {options}
                    element="#material-picker"
            >
                <div class="flatpickr" id="material-picker">
                    <TextField
                            hint="Ab wann bist du etwa vor Ort?"
                            class="mt-3"
                            placeholder={eventTime.getHours() + ":" + eventTime.getMinutes()}
                            data-input
                    >
                        Uhrzeit
                        <div slot="append">
                            <a title="Uhrzeit auswählen" data-toggle>
                                <Icon path={mdiClock}/>
                            </a>
                        </div>
                    </TextField>
                </div>
            </Flatpickr>
            <TextField
                    hint="Wenn du eine E-Mail Adresse angibst wirst du über Änderungen deiner Registrierung informiert"
                    class="mt-3"
                    bind:value={email}
                    placeholder=""
                    rules={emailRules}
            >
                E-Mail (optional)
            </TextField>
        </CardText>
        <CardActions>
            <Button on:click={() => dispatch("cancel")} text>Abbrechen</Button>
            <Button on:click={() => handleSubmit()} text class="light-green lighten-2">Registrieren</Button>
        </CardActions>
    </Card>
</Dialog>
