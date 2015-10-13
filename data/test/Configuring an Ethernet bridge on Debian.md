## Configuring an Ethernet bridge on Debian

In some cases it may be needed to configure an Ethernet interface as a networking brigde, for example it is a common setup for some virtualization hosts/hypervisors. With the bridge it is achieved that
the guest systems can communicate with the outside through the interface eth0, each guest having its own IP address. When configured as a bridge, your interface acts as sort of a network switch.   Here's how to configure a static bridge (there's another way, using the brctl command, but that configuration would be lost after a reboot).

The Debian package bridge-utils contains an extension for /etc/network/interfaces. Instead of eth0, you define br0 and with "bridge\_ports all" you tell all existent LAN interfaces (e.g. eth0) to bind to the bridge. With "bridge\_fd 0" you set the forwarding delay for interfaces joining the bridge to zero.  

Install the bridge-utils:

    apt-get install bridge-utils

! If you configure your device over the network, have a look at my post [Define commands for later execution / auto-restore backup configurations][] before continuing, to avoid locking yourself out of
the system.

Edit your interface configuration:

    vi /etc/network/interfaces

    auto lo br0
    iface lo inet loopback

    # The primary network interface
    allow-hotplug br0
    iface br0 inet static
    address 10.1.1.10
    netmask 255.255.255.0
    gateway 10.1.1.1
    bridge_ports all
    bridge_fd 0

When you bring up the bridge br0 now, it will be your new network interface. Check with `brctl show` afterwards.

    ifup br0
    brctl show

    bridge name bridge id STP enabled interfaces
    br0 8000.00248c24f2af no eth0

  [Define commands for later execution / auto-restore backup configurations]: http://kb.haeringer.org/define-commands-for-later-execution-auto-restore-backup-configurations/
    "Define commands for later execution / auto-restore backup configurations"
