## How to set up a DLNA (UPnP) Media Server with MiniDLNA

MiniDLNA is a Linux server software with the aim of being fully compliant with DLNA/UPnP-AV clients. It is developed by a NETGEAR employee for the ReadyNAS product line. It is a great, lightweight, fast and simple alternative to older media servers like TwonkyServer or Mediatomb. This is about how to set up MiniDLNA on a Debian-based Linux.  

Download source code:

    wget http://sourceforge.net/projects/minidlna/files/minidlna/1.0.23/minidlna_1.0.23_src.tar.gz

To compile and run the the software, install the following prerequisites:

    apt-get install libexif-dev libjpeg8-dev libid3tag0-dev libflac-dev libvorbis-dev sqlite3 libsqlite3-dev libavformat-dev libuuid1

Unzip the downloaded archive and change into the unzipped directory:

    tar -xzf minidlna_1.0.23_src.tar.gz
    cd minidlna-1.0.23

Compile the program:

    make

Copy the binary to /usr/sbin and the configuration file to /etc:

    cp minidlna /usr/sbin 
    cp minidlna.conf /etc/ 

There's also a start-stop script for the daemon that we copy to /etc/init.d, rename it and make it executable:

    cp linux/minidlna.init.d.script /etc/init.d/
    mv /etc/init.d/minidlna.init.d.script /etc/init.d/minidlna
    chmod 755 /etc/init.d/minidlna

Adjust the basic parameters in the config file (there are more of course; just see through the options):

    vi /etc/minidlna.conf

    media_dir=/mediadirectory
    presentation_url=http://serverIPaddress:8200

Start the daemon:

    /etc/init.d/minidlna start

If you want the daemon to automatically start after a system reboot, add minidlna to the start scripts:

    update-rc.d minidlna defaults 

MiniDLNA now needs some minutes to build its database with your media data, and then your UPnP clients are ready to discover the server.  
