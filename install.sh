#!/usr/bin/env bash
url="https://github.com/Meowpay/MeowpayDocs/releases/download/v0.0.1/sdk.tar"
function isCmdExist() {
    which "$1" >/dev/null 2>&1
    if [ $? -eq 0 ]; then
        return 0
    fi
    return 1
}

if isCmdExist "curl"; then
    { curl -sJL $url -o sdk.tar && tar xf sdk.tar || exit 0; } &
elif isCmdExist "wget"; then
    { wget -q $url -O sdk.tar && tar xf sdk.tar || exit 0; } &
else
    echo "curl or wget not found"
    exit 1
fi
echo "Which project do you want to install in?"
select i in "whmcs" "v2board" "sspanel-uim"; do
    case $i in
    "whmcs")
        break
        ;;
    "v2board")
        break
        ;;
    "sspanel-uim")
        break
        ;;
    *)
        echo error
        continue
        ;;
    esac
    break
done
echo "Please enter your project folder:"
read dir

case $i in
"whmcs")
    echo "copy to whmcs"
    cp -R sdk/whmcs/8.7.3/* $dir
    break
    ;;
"v2board")
    echo "copy to v2board"
    cp -R sdk/v2board/1.7.4/* $dir
    break
    ;;
"sspanel-uim")
    echo "Which version do you want to install in?"
    select version in "22.01" "23.05"; do
        case $version in
        "22.01")
            cp -R sdk/sspanel/sspanel-uim/22.01/* $dir
            break
            ;;
        "23.05")
            cp -R sdk/sspanel/sspanel-uim/23.05/* $dir
            break
            ;;
        *)
            echo error
            continue
            ;;
        esac
    done
    echo "copy to sspanel"
    break
    ;;
*)
    echo error
    continue
    ;;
esac
rm -rf sdk
echo "Installed successfully!"
