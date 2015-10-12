Generally I have always thought software raids to be insecure, difficult to handle and more a risk than a safety measure. But a RAID1 with the Linux software **mdadm** is a safe mirror of your harddisk - in case of a HD failure, you can put the remaining disk into any computer and boot from it. And it's easy to handle. One way to configure it on Debian is with the Debian installer during the OS installation, which will be described here.

First, a brief explanation of how software raids work - this is needed to understand the installation process. In a hardware raid, devices exist as hard disks. In a software raid, those devices are partitions that are marked as “Physical volumes for RAID”. Those partitions are then combined into a software RAID array. This array contains normal partitions as they are present on every normal non-raid hard disk.  

I wrote this because I think that the setup process is not self-explanatory and can be a bit confusing.

1. During the Debian installation, choose manual partitioning

2. Select the first harddisk (sda) and create a new partition table

3. Choose the newly created free space and create new partitions (primary), selecting "Use as: physical volume for RAID" for each one. This is where you define your own filesystem structure, so the below is only an example:  
  - First RAID partition of 2-4 GB for the root file system /  
  - Second RAID partition for swap, 2 times RAM  
  - Third RAID partition for data, rest of HD space

4. Select the second hard disk (sdb) and repeat all steps above

5. When finished, select "Configure software RAID" and follow the on-screen instructions:  
  - Create MD device  
  - RAID1  
  - Number of active devices = 2  
  - Spare devices = 0  
  - Repeat for active devices = sda1 + sdb1, sda2 + sdb2, sda3 + sdb3  
  - Finish

6. Back in the partitions overview, select the partition of the first RAID device

7. Choose "Use as: do not use" from the menu and change it to ext4 (or whatever you need) file system, mount point /, done

8. For the second RAID partition, change "Use as" to swap area

9. For the third one, change to ext4, mount point "Enter manually" and name it e.g. /data

10. Finish partitioning

11. After rebooting, when the installation has finished, verify your RAID configuration with cat /proc/mdstat

Grub2 only gets installed on the first HD, sda, by default. Install Grub2 also on the second HD to make it bootable in case HD1 fails:

    grub-install /dev/sdb

Done.  
  

**Harddisk Failure**

If you test your RAID1 by removing one HD and installing it back again; your RAID may not come up correct again automatically. Check with `cat /proc/mdstat`. In case of a partially degraded array, fully degrade it first:

    mdadm --manage /dev/md0 --fail /dev/sdb1
    mdadm --manage /dev/md1 --fail /dev/sdb2
    mdadm --manage /dev/md2 --fail /dev/sdb3

    mdadm --manage /dev/md0 --remove /dev/sdb1
    mdadm --manage /dev/md1 --remove /dev/sdb2
    mdadm --manage /dev/md2 --remove /dev/sdb3

At this point, your system is like having installed a new second HD (sdb) after a real failure. If that's the case (i.e. the disk is empty), copy the partition table of /dev/sda to /dev/sdb:

    sfdisk -d /dev/sda | sfdisk --force /dev/sdb

Afterwards, remove any remains of a previous RAID array from /dev/sdb:

    mdadm --zero-superblock /dev/sdb1
    mdadm --zero-superblock /dev/sdb2
    mdadm --zero-superblock /dev/sdb3

...and add /dev/sdb back to the array:

    mdadm -a /dev/md0 /dev/sdb1
    mdadm -a /dev/md1 /dev/sdb2
    mdadm -a /dev/md2 /dev/sdb3

If there are error messages and you have problems rebuilding the array, check the config file /etc/mdadm/mdadm.conf - your array should be listed there. If it's not, issue the following command to add it to the config file:

    mdadm --examine --scan --config=mdadm.conf >> /etc/mdadm/mdadm.conf

Check the array status with `cat /proc/mdstat`. After the synchronisation has finished, you should have a fully working RAID1 again.

One last tip: In case the md1 device of your RAID array is shown as "resync=PENDING" and doesn't leave that state anymore (can happen after a hard reboot, e.g. because of a power outage), this can easily fixed with

    mdadm --readwrite /dev/md1
