## Cisco config output without breaks

By default, the output when you do a 'sh run' on Cisco devices is divided into segments and you have to press 'Space' many times to show the whole config. This can be annoying when you have PuTTY logging enabled and quickly want to save the running config to your log files (without copy&paste/TFTP/etc), because the interruptions get logged as well.

But there are possibilities to show the whole config at once:

On the **ASA**:

    config# no pager

Back to default:

    config# pager 24

On **IOS**devices:

    term length 0