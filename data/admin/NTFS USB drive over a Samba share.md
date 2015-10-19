## Access an NTFS USB drive over a Samba share

I needed to plug an NTFS formatted USB harddisk into my Debian home server (with Samba file services already running on it) and access it from my PCs in the network. To achieve full read/write access for the Windows users, you need the driver NTFS-3G.

Install ntfs-3g

    apt-get install ntfs-3g

Create the mointpoint for the NTFS disk, e.g.

    mkdir /mnt/usb/ntfs

Add a line to the fstab file ("umask=000" has the effect to allow write access for all users):

    /dev/sdb1 /mnt/usb/ntfs ntfs-3g defaults,rw,umask=000 0 0

Mount the device:

    mount /dev/sdb1 /mnt/usb/ntfs 

I then added an additional share to my Samba smb.conf so I can mount the USB drive as a separate network drive in Windows:

    [usb]
       comment = USB device connected to server
       path = /mnt/usb/ntfs
       guest ok = yes
       browseable = yes
       read only = no
