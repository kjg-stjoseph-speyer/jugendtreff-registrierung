<script>
  import ExpansionPanels, {ExpansionPanel} from 'svelte-materialify/src/components/ExpansionPanels';

  export let events;
</script>


<ExpansionPanels accordion>
    {#each events as event}
        <ExpansionPanel>
            <span slot="header">{event.time}</span>
            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Repellat amet natus
            obcaecati molestiae quas mollitia error modi atque aliquam esse.
        </ExpansionPanel>
    {/each}
</ExpansionPanels>
