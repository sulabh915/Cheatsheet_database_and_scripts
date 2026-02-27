
Step 1: Install Nerd Fonts
Icons require a Nerd Font. You already have SpaceMono Nerd Font files — install them:
```bash
mkdir -p ~/.local/share/fonts
cp SpaceMonoNerdFont*.ttf ~/.local/share/fonts/
fc-cache -fv
```

Then set your terminal font to SpaceMono Nerd Font in preferences.

⚡ Step 2: Install Oh My Zsh
```bash
sh -c "$(curl -fsSL https://raw.githubusercontent.com/ohmyzsh/ohmyzsh/master/tools/install.sh)"
```


🎨 Step 3: Install Powerlevel10k
```bash
git clone --depth=1 https://github.com/romkatv/powerlevel10k.git \
  ${ZSH_CUSTOM:-$HOME/.oh-my-zsh/custom}/themes/powerlevel10k
```




Edit ~/.zshrc:
ZSH_THEME="powerlevel10k/powerlevel10k"



🔧 Step 4: Install Plugins
```bash
git clone https://github.com/zsh-users/zsh-syntax-highlighting.git \
  ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-syntax-highlighting

git clone https://github.com/zsh-users/zsh-autosuggestions.git \
  ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autosuggestions

git clone https://github.com/marlonrichert/zsh-autocomplete.git \
  ${ZSH_CUSTOM:-~/.oh-my-zsh/custom}/plugins/zsh-autocomplete
```

Edit ~/.zshrc:
plugins=(git zsh-syntax-highlighting zsh-autosuggestions zsh-autocomplete)


📦 Step 5: Aliases for lsd and batcat
At the bottom of ~/.zshrc:
# lsd aliases
alias ls='lsd'
alias ll='lsd -l'
alias la='lsd -a'
alias lla='lsd -la'
alias lt='lsd --tree'

# batcat aliases (batcat is bat on Debian/Kali)
alias cat='batcat'
alias bat='batcat'
alias catn='batcat --style=plain'   # no extra decorations
alias catl='batcat --style=plain --paging=never'  # plain + no pager



🖋️ Step 6: Enable Vi Mode
Add:
bindkey -v



🎨 Step 7: Customize Powerlevel10k for Username + Host + Icon
Open ~/.p10k.zsh and edit:
```bash
typeset -g POWERLEVEL9K_LEFT_PROMPT_ELEMENTS=(
  user
  os_icon
  host
  dir
  vcs
)
```


# Always show user and host
typeset -g POWERLEVEL9K_ALWAYS_SHOW_USER=true
typeset -g POWERLEVEL9K_ALWAYS_SHOW_HOST=true



#### Reload everything
```bash
source ~/.zshrc
```


##### use p10k configure
```bash
p10k configure #here you configure your own neeeds
```

change .~/.p10k.zsh file
```bash 
typeset -g POWERLEVEL9K_LEFT_PROMPT_ELEMENTS=(
     user
     os_icon                 # os identifier
     host
     dir                     # current directory
     vcs                     # git status
     prompt_char             # prompt symbol
   )


#################################[ os_icon: os identifier ]##################################
 # OS identifier color.
typeset -g POWERLEVEL9K_OS_ICON_FOREGROUND=
# Custom icon.
typeset -g POWERLEVEL9K_OS_ICON_CONTENT_EXPANSION='🔥'
typeset -g POWERLEVEL9K_HOST_CONTENT_EXPANSION='%m '   # %m = hostname



 
# Username colors
typeset -g POWERLEVEL9K_USER_FOREGROUND=81   # light blue
typeset -g POWERLEVEL9K_USER_BACKGROUND=0    # black/transparent
 
 

 

# Hostname colors
typeset -g POWERLEVEL9K_HOST_FOREGROUND=2    # green
typeset -g POWERLEVEL9K_HOST_BACKGROUND=0    # black/transparent




```


#### Reload everything
```bash
source ~/.zshrc
```



> [!NOTE] Title
> Use btop for advance process management
