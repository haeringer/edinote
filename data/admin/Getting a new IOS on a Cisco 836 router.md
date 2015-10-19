## Getting a new IOS on a Cisco 836 router

Some time ago I was struggling with getting a new IOS version on a 836 router. From the newer 800 series routers, 876 or 881 etc., I was used to configure a VLAN on one of the Ethernet interfaces; then you're able to reach the interface via TFTP at the IP address of the VLAN. The problem with the 836 is that it doesn't support VLANs, so your interface doesn't have an IP address. The solution is to copy the IOS in **ROMmon mode**.

1. Connect your NIC with Ethernet interface 1 of the router (it has to be \#1)

2. Reboot the router and press break during the boot process to get into ROMmon mode  

3. If you want to, check what's in the flash memory:

    rommon 1 > dir flash:

4. Configure IP information with the following commands:

`rommon 2 > IP_ADDRESS=192.168.2.100` (use an address in the same subnet as your NIC)  
`rommon 3 > IP_SUBNET_MASK=255.255.255.0` (the subnet that is configured on your NIC)  
`rommon 4 > DEFAULT_GATEWAY=192.168.2.2` (the address of your NIC)  
`rommon 5 > TFTP_SERVER=192.168.2.2` (the address of your NIC)  
`rommon 6 > TFTP_FILE=c836-k9o3y6-mz.123-4.T.bin` (filename of the IOS to download)

5. Initialize download of the IOS file with tftdnld:

    rommon 7 > tftpdnld

6. You will get a warning that all existing data on flash will be lost, because the command overwrites everything. But on the 836, you normally don't need anything else than the IOS. Proceed with 'y' and watch the copy process.

This works with other routers such as 2800/3800, too.
