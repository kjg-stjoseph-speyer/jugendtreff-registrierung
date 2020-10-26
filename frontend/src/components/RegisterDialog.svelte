<script>
  import { createEventDispatcher } from 'svelte'
  import {mdiClock} from "@mdi/js";
  import { Card, CardTitle, CardActions, CardText, Dialog, Button, TextField, Icon } from 'svelte-materialify/src';
  import Flatpickr from "svelte-flatpickr";

  const dispatch = createEventDispatcher();

  const emailPattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  let name = "";
  let email = "";
  let time = null;

  // field verification
  const nameRules = [(v) => !!v || 'Pflichtangabe!', (v) => v.length <= 25 || 'Max. 25 Buchstaben'];
  const emailRules = [
    (v) => {
      return emailPattern.test(v) || 'Ungültige E-Mail Adresse';
    },
  ];

  let options = {
    enableTime: true,
    noCalendar: true,
    time_24hr: true,
    dateFormat: "H:i",
    disableMobile: true,
    wrap: true,
    onChange: (selectedDates, dateStr, instance) => {
      time = selectedDates[0].getTime()
    }
  };

  $: {
    time = eventTime.getTime();
  }

 const handleSubmit = () => {
   const submitName = name === "" ? defaultName : name;
   const submitEmail = email === "" ? defaultEmail : email;

   if (submitName.length > 0 && submitName.length <= 25 && (submitEmail === "" || emailPattern.test(submitEmail))) {
       dispatch("save", {name: submitName, time, email: submitEmail});
   }else {
     console.log("Validation failed", defaultName, defaultEmail)
   }
  }

  export let active;
  export let eventName;
  export let eventTime;
  export let defaultName = "";
  export let defaultEmail = "";
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
                    placeholder={defaultName}
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
                            placeholder={eventTime.getHours() + ":" + eventTime.getMinutes().toString().padStart(2, '0')}
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
                    placeholder={defaultEmail}
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
