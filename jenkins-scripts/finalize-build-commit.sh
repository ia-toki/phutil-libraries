#!/bin/bash

DIFF_ID=$1
STATUS=$2
MESSAGE=$3
PH_ID=$4

# Tell Harbormaster
echo "{\"buildTargetPHID\" : \"${PH_ID}\", \"type\" : \"${STATUS}\"}" | arc call-conduit harbormaster.sendmessage
