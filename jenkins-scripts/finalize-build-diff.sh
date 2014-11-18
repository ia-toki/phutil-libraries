#!/bin/bash

DIFF_ID=$1
STATUS=$2
MESSAGE=$3

# Update test status
echo "{\"diff_id\" : ${DIFF_ID}, \"name\" : \"Jenkins\", \"result\" : \"${STATUS}\"}" |  arc call-conduit differential.updateunitresults

# Get diff object
DIFF=$(echo "{\"ids\" : [${DIFF_ID}]}" | arc call-conduit differential.querydiffs)

# Parse base commit and revision ID
BASE_COMMIT=$(echo $DIFF | jq .response[].sourceControlBaseRevision | sed "s/\"//g")
REV_ID=$(echo $DIFF | jq .response[].revisionID | sed "s/\"//g")

# Add build message as comment on revision page
echo "{\"revision_id\" : ${REV_ID}, \"message\" : \"Build for **Diff ${DIFF_ID}** finished: ${MESSAGE}\"}" |  arc call-conduit differential.createcomment

# Delete the revision branch
git checkout ${BASE_COMMIT}
git branch -D $(git branch --list arcpatch-D*)
