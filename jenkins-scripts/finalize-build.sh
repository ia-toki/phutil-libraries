#!/bin/bash

DIFF_ID=$1
STATUS=$2
MESSAGE=$3
PH_ID=$4

# Suppress output
set +x

if [ "${PH_ID}" == "0" ] ; then
        ../iatoki-commons/jenkins-scripts/finalize-build-diff.sh ${DIFF_ID} ${STATUS} ${MESSAGE} ${PH_ID}
else
        ../iatoki-commons/jenkins-scripts/finalize-build-commit.sh ${DIFF_ID} ${STATUS} ${MESSAGE} ${PH_ID}
fi

# Bring back output
set -x
