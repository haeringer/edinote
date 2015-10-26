# Ubuntu Server 14.04 LTS

### New user
```
adduser ben
gpasswd -a ben sudo
visudo     // alternatively

    root    ALL=(ALL:ALL) ALL
    ben     ALL=(ALL:ALL) ALL
```

### SSH
```
ssh-copy-id ben@62.141.39.200
sudo vi /etc/ssh/sshd_config

    PermitRootLogin no
    PasswordAuthentication no
    
sudo service ssh restart
```

### Time
```
sudo dpkg-reconfigure tzdata
sudo apt-get update
sudo apt-get install ntp
```

### Firewall
```
sudo apt-get install ufw
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
sudo ufw status
```

### Automatic security updates
```
sudo apt-get install unattended-upgrades
sudo dpkg-reconfigure -plow unattended-upgrades
sudo vi /etc/apt/apt.conf.d/50unattended-upgrades
    Unattended-Upgrade::Automatic-Reboot "false";
    Unattended-Upgrade::Automatic-Reboot-Time "02:00";
    Unattended-Upgrade::Remove-Unused-Dependencies "true";
    Unattended-Upgrade::Mail "admin@haeringer.org";
```

### Error on newly installed VPS

*Fix for "The following signatures couldn't be verified because the public key is not available" with `apt-get update`:*
```
gpg --keyserver hkp://keyserver.ubuntu.com:80 --recv 3B4FE6ACC0B21F32
gpg --export --armor 3B4FE6ACC0B21F32 | sudo apt-key add -
gpg --keyserver hkp://keyserver.ubuntu.com:80 --recv 40976EAF437D05B5
gpg --export --armor 40976EAF437D05B5 | sudo apt-key add -
```

*Fix for Perl "warning: Setting locale failed"*
```
sudo locale-gen en_US.UTF-8
sudo update-locale LANG=en_US.UTF-8
sudo vi /etc/environment

    LC_ALL="en_US.utf8"
    
reboot
```
