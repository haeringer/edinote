## Define commands for later execution

Sometimes it can be critical to configure a remote device, e.g. if you have to configure something on the network interface over which you reach the machine. But there are ways to ensure that you won't be locked out forever in case something goes wrong.

**On Linux**, there's the commmand "at". You can define commands for later execution with it, without setting up a cron job. For example, if you have to change something on /etc/network/interfaces, you only need to make a backup of the previous configuration and tell the system to copy back the backup at a specific time.

Before configuring anything, make a backup:

    cp /etc/network/interfaces /etc/network/interfaces.bak

Define the "rescue jobs" with `at` (if "at" is unknown, install via `apt-get install at`):  

    echo "cp /etc/network/interfaces.bak /etc/network/interfaces" | at 11:30
    echo "ifdown eth0" | at 11:31
    echo "ifup eth0" | at 11:32
    # or alternatively:
    echo "ifup eth0" | at now + 1min

Show your open jobs with `atq`:

    root@SERVER:/home/ben# atq
    1 Tue Sep 20 11:30:00 2010 a root
    2 Tue Sep 20 11:31:00 2010 a root
    3 Tue Sep 20 11:32:00 2010 a root

Delete jobs with `atrm`:

    atrm 2

Now you can safely configure the interface, and in case anything goes wrong, you can be pretty sure that you will be able to reach the system again at 11:31 ;)

 

**On Cisco networking devices** for example, there's also a way to restore the original configuration, but things are a little different: As you probably know, commands have an immediate effect on the systems operation, unlike Linux where processes have to be restarted after changing their configuration. But the running-config needs to be saved as startup-config, otherwise any configurations will be lost after a reboot. You can use this fact to restore the previous configuration in case something went wrong and you're not able to reach a remote device anymore.

Simply issue the command "reload in \<minutes\>" to tell the device that it should reload in your desired amount of time.

    R3# reload in 15
    Reload scheduled in 15 minutes
    Proceed with reload? [confirm]
    R3# 

You will get a warning every five minutes or so that the device is going to reboot. When you have finished your configuration successfully, don't forget to cancel the reload!

    R3# reload cancel
    R3#

    ***
    *** --- SHUTDOWN ABORTED ---
    ***

    R3#
