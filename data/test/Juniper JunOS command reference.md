When it comes to CLI syntax and handling, some network hardware vendors more or less try to copy or at least lean on Cisco's IOS. Some may call it lame, but it definitely makes life easier, because you get on an unknown device and the chance to just guess the right commands is pretty high. Juniper is different. Being mainly used to Cisco and Enterasys, I was pretty much lost - although there are still similarities. Here's an overview of some basic commands for Juniper's JunOS, especially for people coming from Cisco IOS ;) 
First Hint: Auto-completion is done with Space instead of Tab.

Show complete configuration in JunOS hierarchy style:

    > show configuration

A more IOS-like `show run` without the hierarchy structure:

    > show configuration | display set

Go into config mode (`conf t`):<!--more-->

    > configure

Go into subcategory, e.g. interfaces:

    # edit interfaces fe-1/0/0 unit 0

Three levels/categories up:

    # up 3

Equivalents to IOS pipe commands `.. | include` and `.. | exclude`:

    # .. | match
    # .. | except

The following is a very cool feature of JunOS that IOS doesn't offer. Commands are not transfered to the running configuration immediately, but are saved to a temporary storage. You can verify a set of commands before commiting and you can set an automatic rollback in the event you messed up something and cannot access the device anymore. So no need to restart the device for a rollback like on IOS with `reload in ..`

Verify commands:

    # commit check

Commit the commands and transfer them to the running config:

    # commit

Commit the commands and set automatic rollback if the change was not confirmed after 1 minute:

    # commit confirmed 1

Show all uncommited commands:

    # show | compare

Delete all uncommited commands:

    # rollback

Equivalent to IOS `show ip interface brief`:

    # show interfaces terse

IOS `show interface fe1/0/1` & plus L2 error output:

    # show interfaces fa-1/0/1 detail
    # show interfaces fa-1/0/1 extensive

VLAN overview (note: interfaces without descriptions are not displayed!):

    # show interfaces descriptions

VLAN configuration:

    # show conf interface xe-2/2/0.2

STP config:

    # show 802

Configure VLAN tagging for VLAN 150, a logical interface 150 (IOS
subinterface = JunOS logical interface unit!) + IP adress in subnet
.150:

    # edit interfaces fe-1/0/1
    # set vlan-tagging
    # set unit 150 vlan-id 150
    # set unit 150 family inet address 192.168.150.1/24

IOS `show ip route`; set static + default route:

    # show route
    # set static route 10.11.0.0/24 next-hop 10.210.8.190
    # set static route default next-hop 10.210.8.190

Routing troubleshooting - show unused routes etc.:

    # show route hidden (extensive)
    # show route 172.16.24.0/21 (exact) (detail/extensive)

OSPF:

    # edit protocols ospf
    # set area 2 interface fe-0/0/0.0
    # set area 2 stub
