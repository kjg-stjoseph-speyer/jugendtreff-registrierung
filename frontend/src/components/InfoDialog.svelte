<script>
  import { createEventDispatcher } from 'svelte'
  import { Card, CardTitle, CardActions, CardText, Dialog, Button} from 'svelte-materialify/src';

  const dispatch = createEventDispatcher();

  export let show = false;
</script>

<Dialog persistent bind:active={show}>
    <Card>
        <CardTitle>Hygienekonzept ab 1.12.2021</CardTitle>
        <CardText>
            <ol>
                <li>2G+: Zutritt nur für vollständig Genesene und Geimpfte mit <b>zusätzlichem</b> negativem Schnelltest</li>
                <li>Zutritt nur mit Maske! Diese ist generell immer zu tragen, außer wenn ich an meinem Platz am Tisch sitze!</li>
                <li>Zutritt nur für Vereinsmitglieder</li>
                <li>Immer Kontaktformular unterschreiben</li>
                <li>Ich halte ausreichend Abstand zu anderen Menschen!</li>
                <li>Pro Toilette darf sich maximal eine Person aufhalten!</li>
                <li>Eine offizielle Tanzfläche gibt es nicht. Tanzen ist natürlich in Ordnung, aber nur mit Abstand und Maske!</li>
                <li>Jeder Besucher trinkt aus seiner eigenen Flasche, seinem eigenen Glas</li>
                <li>Vor Verlassen des Kellers entsorgt jeder Gast selbst seinen Müll. Dazu zählt auch, leere Flaschen in die dafür vorgesehenen Getränkekisten zu stellen und sein Glas zu spülen!</li>
                <li>Hände regelmäßig waschen und desinfizieren</li>
                <li>Wer sich nicht an die Regeln hält, wird des Kellers verwiesen</li>
            </ol>
        </CardText>
        <CardActions>
            <Button on:click={() => dispatch("close")} text>Schließen</Button>
        </CardActions>
    </Card>
</Dialog>
