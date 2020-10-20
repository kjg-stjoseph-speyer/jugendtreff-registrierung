<script>
  import { createEventDispatcher } from 'svelte'
  import { Card, CardTitle, CardActions, CardText, Dialog, Button} from 'svelte-materialify/src';

  const dispatch = createEventDispatcher();

  export let show = false;
</script>

<Dialog persistent bind:active={show}>
    <Card>
        <CardTitle>Hygiene-Regeln</CardTitle>
        <CardText>
            <ol>
                <li>Zutritt nur für Vereinsmitglieder der KJG St. Joseph Speyer!</li>
                <li>Zutritt nur mit Maske! Diese ist generell immer zu tragen, außer wenn ich an meinem Platz am Tisch sitze!</li>
                <li>Ich halte ausreichend Abstand zu anderen Menschen!</li>
                <li>Pro Toilette darf sich maximal eine Person aufhalten!</li>
                <li>Eine offizielle Tanzfläche gibt es nicht. Tanzen ist natürlich in Ordnung, aber nur mit Abstand und Maske!</li>
                <li>Trinkspiele oder ähnliche Tätigkeiten sind strikt untersagt. Jeder Besucher trinkt aus seiner eigenen Flasche, seinem eigenen Glas!</li>
                <li>Vor Verlassen des Kellers entsorgt jeder Gast selbst seinen Müll. Dazu zählt auch, leere Flaschen in die dafür vorgesehenen Getränkekisten zu stellen und sein Glas zu spülen!</li>
                <li>Immer Kontaktformular unterschreiben!</li>
                <li>Hände regelmäßig waschen!</li>
                <li>Da wir für Durchzug sorgen müssen und daher oft Tür und Fenster offen stehen werden, ist ab 22 Uhr für eine angemessene Lautstärke zu sorgen. Daran muss jeder mitwirken!</li>
                <li>Die Tische dürfen nicht umgestellt werden, die Anzahl der Stühle an einem Tisch ebenfalls nicht!</li>
                <li>Wer sich nicht an die Regeln hält, wird des Kellers verwiesen und muss die "Jugendtreff Reservierung"-WhatsApp-Gruppe verlassen!</li>
            </ol>
        </CardText>
        <CardActions>
            <Button on:click={() => dispatch("close")} text>Schließen</Button>
        </CardActions>
    </Card>
</Dialog>
